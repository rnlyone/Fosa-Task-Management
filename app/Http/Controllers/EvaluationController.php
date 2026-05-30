<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationEntry;
use App\Models\EvaluationSubmission;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    // President/VP: list all evaluations
    public function index()
    {
        $evaluations = Evaluation::with('event')
            ->latest()
            ->paginate(10);

        return view('evaluations.index', compact('evaluations'));
    }

    // President/VP: create evaluation for an event
    public function create()
    {
        $events = Event::whereDoesntHave('evaluation')
            ->orderByDesc('id')
            ->get(['id', 'name', 'status']);

        return view('evaluations.create', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id'   => ['required', 'exists:events,id', 'unique:evaluations,event_id'],
            'opens_at'   => ['nullable', 'date'],
            'closes_at'  => ['nullable', 'date', 'after_or_equal:opens_at'],
        ]);

        $evaluation = Evaluation::create([
            'event_id'  => $data['event_id'],
            'opens_at'  => $data['opens_at'] ?? null,
            'closes_at' => $data['closes_at'] ?? null,
        ]);

        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Evaluation created.');
    }

    public function openEvaluation(Evaluation $evaluation)
    {
        $evaluation->update(['opens_at' => now()]);
        return back()->with('success', 'Evaluation is now open for submissions.');
    }

    public function closeEvaluation(Evaluation $evaluation)
    {
        $evaluation->update(['closes_at' => now()]);
        return back()->with('success', 'Evaluation closed.');
    }

    // President/VP: view results
    public function show(Evaluation $evaluation)
    {
        $evaluation->load('event', 'submissions.evaluator', 'submissions.entries.evaluatedUser');

        $avgRatings     = $evaluation->averageRatings();
        $totalMembers   = User::count();
        $submitted      = $evaluation->submissions()->whereNotNull('submitted_at')->count();

        return view('evaluations.show', compact('evaluation', 'avgRatings', 'totalMembers', 'submitted'));
    }

    // Any member: fill the evaluation form
    public function form(Evaluation $evaluation)
    {
        abort_unless($evaluation->isOpen(), 403, 'This evaluation is not currently open.');

        $user       = Auth::user();
        $submission = $evaluation->submissionByUser($user->id);

        if ($submission && $submission->isSubmitted()) {
            return redirect()->route('evaluations.thankyou', $evaluation)
                ->with('info', 'You have already submitted your evaluation.');
        }

        // All members to evaluate (all organization members)
        $members = User::where('id', '!=', $user->id)->orderBy('name')->get();

        // Pre-fill existing draft entries if any
        $draftEntries = $submission
            ? $submission->entries()->pluck('comment', 'evaluated_user_id')->toArray()
            : [];

        $draftRatings = $submission
            ? $submission->entries()->pluck('rating', 'evaluated_user_id')->toArray()
            : [];

        return view('evaluations.form', compact('evaluation', 'members', 'draftEntries', 'draftRatings'));
    }

    public function submitForm(Request $request, Evaluation $evaluation)
    {
        abort_unless($evaluation->isOpen(), 403, 'This evaluation is not currently open.');

        $user = Auth::user();

        $data = $request->validate([
            'entries'                => ['required', 'array'],
            'entries.*.user_id'      => ['required', 'exists:users,id'],
            'entries.*.rating'       => ['required', 'integer', 'min:1', 'max:5'],
            'entries.*.comment'      => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($evaluation, $user, $data) {
            $submission = EvaluationSubmission::firstOrCreate(
                ['evaluation_id' => $evaluation->id, 'evaluator_id' => $user->id],
            );

            // Remove old entries and replace
            $submission->entries()->delete();

            foreach ($data['entries'] as $entry) {
                EvaluationEntry::create([
                    'submission_id'     => $submission->id,
                    'evaluated_user_id' => $entry['user_id'],
                    'rating'            => $entry['rating'],
                    'comment'           => $entry['comment'] ?? null,
                ]);
            }

            $submission->update(['submitted_at' => now()]);
        });

        return redirect()->route('evaluations.thankyou', $evaluation);
    }

    public function thankyou(Evaluation $evaluation)
    {
        return view('evaluations.thankyou', compact('evaluation'));
    }
}
