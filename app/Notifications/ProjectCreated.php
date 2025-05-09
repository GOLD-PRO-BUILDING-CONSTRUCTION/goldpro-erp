<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ProjectCreated extends Notification
{
    public function __construct(public Project $project) {}

    public function via($notifiable): array
    {
        return ['database']; // نخزن الإشعار في قاعدة البيانات
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'تم إنشاء مشروع جديد',
            'body' => 'تم إنشاء مشروع جديد للعميل: ' . optional($this->project->client)->name,
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'url' => route('filament.admin.resources.projects.edit', ['record' => $this->project->id]),
        ];
    }
}

