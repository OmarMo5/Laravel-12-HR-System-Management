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
        // Convert join_date from "d M, Y" to "Y-m-d"
        if ($this->join_date) {
            try {
                $this->merge([
                    'join_date' => Carbon::createFromFormat('d M, Y', $this->join_date)->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                try {
                    $this->merge([
                        'join_date' => Carbon::parse($this->join_date)->format('Y-m-d')
                    ]);
                } catch (\Exception $e2) {}
            }
        }

        // Convert insurance_start_date from "d M, Y" to "Y-m-d"
        if ($this->insurance_start_date) {
            try {
                $this->merge([
                    'insurance_start_date' => Carbon::createFromFormat('d M, Y', $this->insurance_start_date)->format('Y-m-d')
                ]);
            } catch (\Exception $e) {
                try {
                    $this->merge([
                        'insurance_start_date' => Carbon::parse($this->insurance_start_date)->format('Y-m-d')
                    ]);
                } catch (\Exception $e2) {}
            }
        }

        // Strip any non-digit characters from phone, national_id, insurance_number (backend safety net)
        if ($this->phone_number) {
            $this->merge(['phone_number' => preg_replace('/\D/', '', $this->phone_number)]);
        }
        if ($this->national_id) {
            $this->merge(['national_id' => preg_replace('/\D/', '', $this->national_id)]);
        }
        if ($this->insurance_number) {
            $this->merge(['insurance_number' => preg_replace('/\D/', '', $this->insurance_number)]);
        }
    }

    public function rules(): array
    {
        $userId = $this->id;

        return [
            // 1. Basic Information
            'name'          => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'], // letters & spaces only (Arabic + Latin)
            'phone_number'  => ['required', 'digits:11'],                                   // exactly 11 digits
            'email'         => 'required|email|unique:users,email,' . $userId,
            'company_email' => 'required|email|unique:users,company_email,' . $userId,
            'national_id'   => ['required', 'digits:14'],                                   // exactly 14 digits
            'address'       => 'required|string',

            // 2. Job Information
            'job_title_id'      => 'required|exists:position_types,id',
            'department_id'     => 'nullable|exists:departments,id',
            'manager_id'        => 'nullable|exists:users,id',
            'work_type'         => 'required|string',
            'work_location_job' => 'required|string',

            // 3. Hiring Information
            'join_date'     => 'required|date',
            'contract_type' => 'required|in:permanent,temporary,freelance,consultant,fixed',

            // 4. System Access
            'role_id'  => 'required|exists:role_type_users,id',
            'password' => $userId ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',

            // 5. Salary
            'base_salary'  => 'required|numeric|min:0',
            'advances'     => 'nullable|numeric|min:0',
            'deductions'   => 'nullable|numeric|min:0',
            'allowances'   => 'nullable|numeric|min:0',
            'overtime'     => 'nullable|numeric|min:0',
            'payment_type' => 'required|in:cash,bank_transfer',

            // 6. Insurance
            'insurance_number'     => 'nullable|numeric',
            'insurance_start_date' => 'nullable|date',
            'insurance_status'     => 'required|in:insured,not_insured,willing,not_willing',

            // 7. Additional Info
            'location'         => 'nullable|string',
            'experience_years' => 'nullable|numeric|min:0',
            'gender'           => 'nullable|in:Male,Female',

            // 8. Documents
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex'               => app()->getLocale() === 'ar'
                ? 'الاسم يجب أن يحتوي على حروف فقط بدون أرقام.'
                : 'Name must contain letters only, no numbers.',
            'phone_number.digits'      => app()->getLocale() === 'ar'
                ? 'رقم الهاتف يجب أن يتكون من 11 رقماً بالضبط.'
                : 'Phone number must be exactly 11 digits.',
            'national_id.digits'       => app()->getLocale() === 'ar'
                ? 'الرقم القومي يجب أن يتكون من 14 رقماً بالضبط.'
                : 'National ID must be exactly 14 digits.',
            'insurance_number.numeric' => app()->getLocale() === 'ar'
                ? 'رقم التأمين يجب أن يحتوي على أرقام فقط.'
                : 'Insurance number must contain digits only.',
        ];
    }
}