<?php require "app/includes/header.php"; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="display-6 fw-semibold mb-1">Manage Events</h1>
        <p class="text-secondary mb-0">Review and maintain campus activities.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="index.php?action=upload">Bulk participant upload</a>
        <a class="btn btn-primary" href="index.php?action=create">Add new event</a>
    </div>
</div>

<?php

if (isset($_SESSION['success'])) {

    echo "<div class=\"alert alert-success\" role=\"alert\">" .
        htmlspecialchars($_SESSION['success']) . "</div>";

    unset($_SESSION['success']);
}

?>

<div class="card border-0 shadow-sm">
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">

    <thead>

        <tr>

            <th>ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Target Audience</th>
            <th>Budget</th>
            <th>Status</th>
            <th>Coordinator</th>
            <th>Action</th>

        </tr>

    </thead>

    <tbody>

    <?php while ($event = $events->fetch_assoc()): ?>

        <tr>

            <td>
                <?= htmlspecialchars($event['event_id']) ?>
            </td>

            <td>
                <?= htmlspecialchars($event['event_name']) ?>
            </td>

            <td>
                <?= htmlspecialchars($event['event_date']) ?>
            </td>

            <td>
                <?= htmlspecialchars($event['venue']) ?>
            </td>

            <td>
                <?= htmlspecialchars($event['target_audience']) ?>
            </td>

            <td>RM <?= htmlspecialchars($event['budget']) ?></td>

            <td>
                <?= htmlspecialchars($event['status']) ?>
            </td>

            <td>
                <?= htmlspecialchars($event['coordinator']) ?>
            </td>

            <td>

                <a class="btn btn-sm btn-outline-primary me-1"
                    href="index.php?action=edit&id=<?= $event['event_id'] ?>">Edit</a>
                <a class="btn btn-sm btn-outline-secondary me-1"
                    href="index.php?action=participants&id=<?= $event['event_id'] ?>">Participants</a>
                <a class="btn btn-sm btn-outline-danger"
                    href="index.php?action=delete&id=<?= $event['event_id'] ?>"
                    onclick="return confirm(
                        'Are you sure you want to delete this event?'
                    );">Delete</a>

            </td>

        </tr>

    <?php endwhile; ?>

    </tbody>

</table>
</div>
</div>
<?php require "app/includes/footer.php"; ?>