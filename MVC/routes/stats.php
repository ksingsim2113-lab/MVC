<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/events.php';
require_once DATABASES_DIR . '/registrations.php';

$conn    = getConnection();
$eventId = (int) ($_GET['event_id'] ?? 0);
$userId  = (int) $_SESSION['user_id'];

$event = getEventById($conn, $eventId);

if (!$event || (int) $event['user_id'] !== $userId) {
    header('Location: /event');
    exit;
}

$participants = getParticipantsByEventId($conn, $eventId);


$statusCount = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
foreach ($participants as $p) $statusCount[$p['status']]++;


$genderCount = ['male' => 0, 'female' => 0, 'other' => 0];
foreach ($participants as $p) {
    $g = $p['gender'] ?? 'other';
    $genderCount[$g] = ($genderCount[$g] ?? 0) + 1;
}

$approved    = array_filter($participants, fn($p) => $p['status'] === 'approved');
$checkedIn   = count(array_filter($approved, fn($p) => (int)$p['is_checked_in'] === 1));
$notCheckedIn = count($approved) - $checkedIn;


$ageGroups = ['< 18' => 0, '18-25' => 0, '26-35' => 0, '36-45' => 0, '> 45' => 0];
foreach ($approved as $p) {
    if (empty($p['birthdate'])) continue;
    $age = (int) date_diff(date_create($p['birthdate']), date_create('today'))->y;
    if ($age < 18)       $ageGroups['< 18']++;
    elseif ($age <= 25)  $ageGroups['18-25']++;
    elseif ($age <= 35)  $ageGroups['26-35']++;
    elseif ($age <= 45)  $ageGroups['36-45']++;
    else                 $ageGroups['> 45']++;
}

renderView('event/stats', [
    'event'        => $event,
    'statusCount'  => $statusCount,
    'genderCount'  => $genderCount,
    'checkedIn'    => $checkedIn,
    'notCheckedIn' => $notCheckedIn,
    'ageGroups'    => $ageGroups,
    'total'        => count($participants),
]);