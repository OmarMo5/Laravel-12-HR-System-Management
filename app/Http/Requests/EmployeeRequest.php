<?php
// app/Http/Requests/EmployeeRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // تحويل التاريخ من format "d M, Y" إلى "Y-m-d"
        if ($this->join_date) {
            try {
                $this->merge([
                    'join_date' => Carbon::createFromFormat('d M, Y', $this->join_date)->format('Y-m-d')
                ]);
            } catch (\Exception $e) {}
        }
    }

    public function rules(): array
    {
        $userId = $this->id;

        return [
            // 1. Basic Information
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $userId,
            'company_email' => 'required|email|unique:users,company_email,' . $userId,
            'national_id' => 'required|string|max:50',
            'address' => 'required|string',

            // 2. Job Information
            'job_title_id' => 'required|exists:position_types,id',
            'department_id' => 'required|exists:departments,id',
            'manager_id' => 'nullable|exists:users,id',
            'work_type' => 'required|string',
            'work_location_job' => 'required|string',

            // 3. Hiring Information
            'join_date' => 'required|date',
            'contract_type' => 'required|in:permanent,temporary,freelance,consultant,fixed',

            // 4. System Access
            'role_id' => 'required|exists:role_type_users,id',
            'password' => $userId ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',

            // 5. Salary
            'base_salary' => 'required|numeric|min:0',
            'advances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'payment_type' => 'required|in:cash,bank_transfer',

            // 6. Insurance
            'insurance_number' => 'nullable|string',
            'insurance_start_date' => 'nullable|date',
            'insurance_status' => 'required|in:insured,not_insured,willing,not_willing',

            // 7. Additional Info
            'location' => 'nullable|string',
            'experience_years' => 'nullable|numeric|min:0',
            'gender' => 'nullable|in:Male,Female',


            // 9. Documents
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}