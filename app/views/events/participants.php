<?php require "app/includes/header.php"; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="display-6 fw-semibold mb-1">Registered Participants</h1>
        <p class="text-secondary mb-0">
            <?= htmlspecialchars($event['event_name']) ?>
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="index.php?action=events">Back to events</a>
        <a class="btn btn-primary" href="index.php?action=upload">Upload participants</a>
    </div>
</div>

<?php if (isset($_SESSION['upload_result'])): ?>
    <div class="alert alert-success" role="status">
        Registered <?= (int)$_SESSION['upload_result']['success'] ?> participant(s).
        <?= (int)$_SESSION['upload_result']['failed'] ?> row(s) were skipped.
    </div>
    <?php unset($_SESSION['upload_result']); ?>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $participant): ?>
                    <tr>
                        <td><?= htmlspecialchars($participant['student_id']) ?></td>
                        <td><?= htmlspecialchars($participant['student_name']) ?></td>
                        <td><?= htmlspecialchars($participant['student_email']) ?></td>
                        <td><?= htmlspecialchars($participant['registered_at']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($participants)): ?>
                    <tr>
                        <td class="text-secondary py-4 text-center" colspan="4">
                            No participants registered yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require "app/includes/footer.php"; ?>