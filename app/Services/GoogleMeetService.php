<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\EventDateTime;
use Exception;

class GoogleMeetService
{
    private Client $client;
    private string $calendarId;

    public function __construct()
    {
        $this->calendarId = config('liveclass.google.calendar_id', 'primary');

        $this->client = new Client();
        $this->client->setApplicationName('IOM Live Class');
        $this->client->setScopes(config('liveclass.google.scopes'));

        $credentialsPath = config('liveclass.google.service_account_json');

        // Accept path string or JSON content directly from env
        if (is_file($credentialsPath)) {
            $this->client->setAuthConfig($credentialsPath);
        } else {
            throw new Exception('Google Service Account JSON file not found at: ' . $credentialsPath);
        }

        $this->client->setSubject(null); // Institute-level service account, no impersonation needed
    }

    /**
     * Create a Google Meet link via Google Calendar API.
     *
     * @param string $title      Event title
     * @param string $startTime  ISO 8601 with timezone (e.g. 2026-05-22T10:00:00+06:00)
     * @param string $endTime    ISO 8601 with timezone
     * @param string $timezone   Default Asia/Dhaka
     * @return array ['meet_link', 'event_id']
     */
    public function createMeeting(string $title, string $startTime, string $endTime, string $timezone = 'Asia/Dhaka'): array
    {
        $service = new Calendar($this->client);

        $event = new Event([
            'summary' => $title,
            'start'   => ['dateTime' => $startTime, 'timeZone' => $timezone],
            'end'     => ['dateTime' => $endTime,   'timeZone' => $timezone],
            'conferenceData' => [
                'createRequest' => [
                    'requestId'             => uniqid('iom-', true),
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                ],
            ],
        ]);

        $createdEvent = $service->events->insert(
            $this->calendarId,
            $event,
            ['conferenceDataVersion' => 1]
        );

        $meetLink = $createdEvent->getHangoutLink();

        if (!$meetLink) {
            throw new Exception('Google Meet link was not generated. Ensure Calendar API is enabled and service account has calendar access.');
        }

        return [
            'meet_link' => $meetLink,
            'event_id'  => $createdEvent->getId(),
        ];
    }
}
