<?php
declare(strict_types=1);


require_once DATABASES_DIR . '/events.php';
require_once DATABASES_DIR . '/registrations.php'; 

$conn   = getConnection();
$userId = (int) $_SESSION['user_id'];


$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);


$myRegistrations = getRegistrationsByUserId($conn, $userId); 
foreach ($myRegistrations as &$r) {
    
    $imgs = getEventImages($conn, (int) $r['event_id']);
    $r['cover'] = $imgs[0]['path'] ?? null;
}


$myCreatedEvents = getEventsByUserId($conn, $userId);
foreach ($myCreatedEvents as &$e) {
    $imgs = getEventImages($conn, (int) $e['id']);
    $e['cover'] = $imgs[0]['path'] ?? null;
}


renderView('event/event', [
    'registrations' => $myRegistrations, 
    'events'        => $myCreatedEvents,
    'success'       => $success
]);