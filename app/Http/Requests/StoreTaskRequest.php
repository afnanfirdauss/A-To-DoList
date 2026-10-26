<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['required', Rule::in(['todo', 'in_progress', 'done'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    /**
     * Custom validation logic for project, category, and assignee contextual access.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $user = $this->user();

                // Validate project accessibility
                if ($this->filled('project_id')) {
                    $project = Project::find($this->project_id);
                    if (! $project || ! $project->hasAccess($user)) {
                        $validator->errors()->add('project_id', 'Anda tidak memiliki akses ke proyek yang dipilih.');
                    }

                    // Validate assigned_to belongs to project
                    if ($this->filled('assigned_to') && $project) {
                        $assigneeId = (int) $this->assigned_to;
                        $isOwner = (int) $project->user_id === $assigneeId;
                        $isMember = $project->members()->where('users.id', $assigneeId)->exists();

                        if (! $isOwner && ! $isMember) {
                            $validator->errors()->add('assigned_to', 'Pengguna yang ditugaskan harus merupakan anggota dari proyek ini.');
                        }
                    }
                }

                // Validate category belongs to user
                if ($this->filled('category_id')) {
                    $category = Category::find($this->category_id);
                    if (! $category || (int) $category->user_id !== (int) $user->id) {
                        $validator->errors()->add('category_id', 'Kategori yang dipilih tidak valid atau bukan milik Anda.');
                    }
                }
            },
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'due_date.after_or_equal' => 'Deadline tidak boleh lebih awal dari tanggal mulai tugas.',
            'status.in' => 'Status tugas tidak valid.',
            'priority.in' => 'Prioritas tugas tidak valid.',
        ];
    }
}
