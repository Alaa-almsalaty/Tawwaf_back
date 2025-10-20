<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ReservationState;

class CreateReservationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'client_id' => 'nullable|exists:clients,id',
            'visitor_id' => 'nullable|exists:users,id',
            'family_id' => 'nullable|exists:families,id',
            'package_id' => 'nullable|exists:packages,id',
            'package_room_id' => 'nullable|exists:package_rooms,id',
            //'ticket_id' => 'nullable|exists:tickets,id',
            'number_of_travelers' => 'integer|min:1',
            'has_transportation' => 'boolean',
            'has_ticket' => 'boolean',
            'extra_services' => 'nullable|string',
            'created_by' => 'nullable|exists:users,id',
            'reservation_date' => 'required|date',
            'reservation_state' => ['required', Rule::enum(ReservationState::class)],
            'note' => 'nullable|string',
        ];
    }

    public function createReservation(): array
    {
        return [
            'branch_id' => $this->validated('branch_id') ?? null,
            'client_id' => $this->validated('client_id') ?? null,
            'visitor_id' => $this->validated('visitor_id') ?? null,
            'family_id' => $this->validated('family_id') ?? null,
            'package_id' => $this->validated('package_id') ?? null,
            'package_room_id' => $this->validated('package_room_id') ?? null,
            'ticket_id' => $this->validated('ticket_id') ?? null,
            'has_transportation' => $this->validated('has_transportation') ?? false,
            'has_ticket' => $this->validated('has_ticket') ?? false,
            'number_of_travelers' => $this->validated('number_of_travelers') ?? 1,
            'extra_services' => $this->validated('extra_services') ?? null,
            'created_by' => $this->validated('created_by')?? null,
            'reservation_date' => $this->validated('reservation_date') ?? now(),
            'reservation_state' => $this->validated('reservation_state') ?? 'sent',
            'note' => $this->validated('note') ?? null,
        ];
    }

    public function messages(){
        return [
            'visitor_id.exists' => 'المستخدم غير موجود',
            'package_id.exists' => 'الباقة غير موجودة',
            'family_id.exists' => 'العائلة غير موجودة',
            'client_id.exists' => 'العميل غير موجود',
            'branch_id.exists' => 'الفرع غير موجود',
            'number_of_travelers.integer' => 'عدد المسافرين يجب ان يكون رقما',
            'number_of_travelers.min' => 'عدد المسافرين يجب ان يكون على الاقل 1',
            'has_transportation.boolean' => 'حقل وجود النقل يجب ان يكون صحيح او خطأ',
            'has_ticket.boolean' => 'حقل وجود التذكرة يجب ان يكون صحيح او خطأ',
            'extra_services.string' => 'الخدمات الاضافية يجب ان تكون نصا',
            'created_by.exists' => 'المستخدم الذي انشأ الحجز غير موجود',
            'reservation_date.date' => 'تاريخ الحجز يجب ان يكون تاريخا صحيحا',
            'reservation_state.required' => 'حالة الحجز مطلوبة',
            'reservation_state.in' => 'حالة الحجز غير صحيحة',
            'note.string' => 'الملاحظة يجب ان تكون نصا',
            'package_room_id.exists' => 'الغرفة المختارة غير موجودة',

        ];
    }
}
