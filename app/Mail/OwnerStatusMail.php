<?php

namespace App\Mail;

use App\Models\Owner;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OwnerStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Owner $owner;
    public string $status;
    public ?string $reason;
    public bool $isApproved;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Owner  $owner
     * @param  string  $status  ('approved' | 'rejected' | 'account_verified')
     * @param  string|null  $reason
     */
    public function __construct(Owner $owner, string $status, ?string $reason = null)
    {
        $this->owner = $owner;
        $this->status = $status;
        $this->reason = $reason;
        $this->isApproved = in_array(strtolower($status), ['approved', 'account_verified', Owner::ACCOUNT_VERIFIED]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isApproved
            ? 'Selamat! Akun Owner Lokavino Anda Telah Disetujui'
            : 'Pemberitahuan Status Pendaftaran Akun Owner Lokavino';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.owner-status',
            with: [
                'owner' => $this->owner,
                'status' => $this->status,
                'reason' => $this->reason,
                'isApproved' => $this->isApproved,
                'loginUrl' => route('login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
