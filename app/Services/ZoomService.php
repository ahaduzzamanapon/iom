<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class ZoomService
{
    private string $accountId;
    private string $clientId;
    private string $clientSecret;
    private string $tokenUrl;
    private string $apiBase;

    public function __construct()
    {
        $this->accountId    = config('liveclass.zoom.account_id');
        $this->clientId     = config('liveclass.zoom.client_id');
        $this->clientSecret = config('liveclass.zoom.client_secret');
        $this->tokenUrl     = config('liveclass.zoom.token_url');
        $this->apiBase      = config('liveclass.zoom.api_base');
    }

    /**
     * Get OAuth access token (cached for 55 min, token expires in 60 min).
     */
    public function getAccessToken(): string
    {
        return Cache::remember('zoom_access_token', 3300, function () {
            $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");

            $response = Http::withHeaders([
                'Authorization' => "Basic {$credentials}",
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ])->post($this->tokenUrl, [
                'grant_type' => 'account_credentials',
                'account_id' => $this->accountId,
            ]);

            if (!$response->successful()) {
                throw new Exception('Zoom token error: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    /**
     * Create a Zoom meeting.
     *
     * @param string $topic     Meeting title
     * @param string $startTime ISO 8601 datetime (e.g. 2026-05-22T10:00:00)
     * @param int    $duration  Duration in minutes
     * @return array  ['join_url', 'start_url', 'meeting_id']
     */
    public function createMeeting(string $topic, string $startTime, int $duration = 60): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->apiBase}/users/me/meetings", [
                'topic'      => $topic,
                'type'       => 2,              // Scheduled meeting
                'start_time' => $startTime,     // UTC ISO8601
                'duration'   => $duration,
                'timezone'   => 'Asia/Dhaka',
                'settings'   => [
                    'join_before_host'  => true,
                    'waiting_room'      => false,
                    'audio'             => 'both',
                    'auto_recording'    => 'none',
                ],
            ]);

        if (!$response->successful()) {
            throw new Exception('Zoom meeting create error: ' . $response->body());
        }

        $data = $response->json();

        return [
            'meeting_id' => (string) $data['id'],
            'join_url'   => $data['join_url'],
            'start_url'  => $data['start_url'],
        ];
    }

    /**
     * Delete a Zoom meeting by meeting ID.
     */
    public function deleteMeeting(string $meetingId): void
    {
        $token = $this->getAccessToken();
        Http::withToken($token)->delete("{$this->apiBase}/meetings/{$meetingId}");
    }
}
