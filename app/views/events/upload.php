<?php require "app/includes/header.php"; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="display-6 fw-semibold mb-0">Bulk Participant Registration</h1>
    <a class="btn btn-outline-secondary" href="index.php?action=events">Back to events</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if ($events->num_rows === 0): ?>
    <div class="alert alert-info" role="status">
        Create an event before registering participants.
    </div>
<?php else: ?>
    <form class="card border-0 shadow-sm p-3 p-md-4"
        action="index.php?action=uploadParticipants"
        method="POST"
        enctype="multipart/form-data"
    >
        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csv_upload_token']) ?>">

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="event_id">Event</label>
                <select class="form-select" id="event_id" name="event_id" required>
                    <?php while ($event = $events->fetch_assoc()): ?>
                        <option value="<?= (int)$event['event_id'] ?>">
                            <?= htmlspecialchars($event['event_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label" for="csv_file">CSV file (maximum 2MB)</label>
                <input class="form-control" id="csv_file" type="file"
                    name="csv_file" accept=".csv,text/csv" required>
                <div class="form-text">
                    Required columns: student_id, student_name, student_email.
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end pt-2">
                <button class="btn btn-primary px-4" type="submit">Upload CSV</button>
            </div>
        </div>
    </form>
<?php endif; ?>

<?php require "app/includes/footer.php"; ?>