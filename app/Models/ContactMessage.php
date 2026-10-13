<?php

namespace App\Models;

use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'locale',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * Libellé traduit de l'objet de la demande.
     */
    public function subjectLabel(): string
    {
        return __("contact.subjects.{$this->subject}", locale: $this->locale);
    }
}
