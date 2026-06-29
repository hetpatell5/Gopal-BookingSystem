<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormTemplate;
use App\Models\FormResponse;

class FormController extends Controller
{
    // ── Admin: list all forms + responses ──────────────────────
    public function index(Request $request)
    {
        $forms          = FormTemplate::withCount('responses')->latest()->get();
        $selectedFormId = $request->input('form_id', optional($forms->first())->id);
        $selectedForm   = $selectedFormId ? FormTemplate::with('responses')->find($selectedFormId) : null;
        $responses      = $selectedForm
            ? $selectedForm->responses()->latest()->get()
            : collect();

        return view('forms.index', compact('forms', 'selectedForm', 'responses', 'selectedFormId'));
    }

    // ── Admin: show create form builder ────────────────────────
    public function create()
    {
        return view('forms.create');
    }

    // ── Admin: save new form template ──────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'fields' => 'required|string', // JSON string from JS
        ]);

        $fields = json_decode($request->fields, true);
        if (!is_array($fields) || empty($fields)) {
            return back()->withErrors(['fields' => 'Please add at least one field.'])->withInput();
        }

        FormTemplate::create([
            'name'        => $request->name,
            'description' => $request->description,
            'fields'      => $fields,
            'is_active'   => true,
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('forms.index')->with('success', 'Form "' . $request->name . '" created successfully.');
    }

    // ── Admin: show edit form builder ──────────────────────────
    public function edit(FormTemplate $form)
    {
        return view('forms.edit', compact('form'));
    }

    // ── Admin: update form template ────────────────────────────
    public function update(Request $request, FormTemplate $form)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'fields' => 'required|string',
        ]);

        $fields = json_decode($request->fields, true);
        if (!is_array($fields) || empty($fields)) {
            return back()->withErrors(['fields' => 'Please add at least one field.'])->withInput();
        }

        $form->update([
            'name'        => $request->name,
            'description' => $request->description,
            'fields'      => $fields,
        ]);

        return redirect()->route('forms.index')->with('success', 'Form updated successfully.');
    }

    // ── Admin: delete form (+ responses cascade) ───────────────
    public function destroy(FormTemplate $form)
    {
        $form->delete();
        return redirect()->route('forms.index')->with('success', 'Form deleted.');
    }

    // ── Public: show form for filling ─────────────────────────
    public function publicShow(FormTemplate $form)
    {
        if (!$form->is_active) {
            abort(404, 'This form is no longer active.');
        }
        return view('forms.public', compact('form'));
    }

    // ── Public: submit form response ──────────────────────────
    public function publicSubmit(Request $request, FormTemplate $form)
    {
        if (!$form->is_active) {
            abort(404);
        }

        // Validate required fields based on form definition
        $rules = [];
        foreach ($form->fields as $field) {
            if (($field['type'] ?? '') === 'section_header') continue;
            if (!empty($field['required'])) {
                $key = 'responses.' . $field['label'];
                $rules[$key] = 'required';
            }
        }
        $request->validate($rules);

        FormResponse::create([
            'form_template_id' => $form->id,
            'data'             => $request->input('responses', []),
            'submitted_by'     => $request->ip(),
        ]);

        return redirect()->route('forms.public.show', $form)->with('submitted', true);
    }

    // ── Admin: delete a single response ──────────────────────
    public function destroyResponse(FormResponse $response)
    {
        $formId = $response->form_template_id;
        $response->delete();
        return redirect()->route('forms.index', ['form_id' => $formId])->with('success', 'Response deleted.');
    }
}
