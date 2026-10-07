<?php require "app/includes/header.php"; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="display-6 fw-semibold mb-0">Edit Event</h1>
    <a class="btn btn-outline-secondary" href="index.php?action=events">Back to events</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row justify-content-center">
       <div class="col-lg-10 col-xl-9">
              <form class="card border-0 shadow-sm p-3 p-md-4"
                     action="index.php?action=update"
                     method="POST"
              >
                     <input type="hidden" name="event_id"
                            value="<?= htmlspecialchars($event['event_id']) ?>">

                     <div class="row g-3">
                            <div class="col-md-6">
                                   <label class="form-label" for="event_name">Event Name</label>
                                   <input class="form-control" id="event_name" type="text"
                                          name="event_name"
                                          value="<?= htmlspecialchars($event['event_name']) ?>"
                                          required maxlength="150">
                            </div>

                            <div class="col-md-6">
                                   <label class="form-label" for="event_date">Event Date</label>
                                   <input class="form-control" id="event_date" type="date"
                                          name="event_date"
                                          value="<?= htmlspecialchars($event['event_date']) ?>"
                                          required>
                            </div>

                            <div class="col-md-6">
                                   <label class="form-label" for="venue">Venue</label>
                                   <input class="form-control" id="venue" type="text"
                                          name="venue"
                                          value="<?= htmlspecialchars($event['venue']) ?>"
                                          required maxlength="150">
                            </div>

                            <div class="col-md-6">
                                   <label class="form-label" for="target_audience">Target Audience</label>
                                   <input class="form-control" id="target_audience" type="text"
                                          name="target_audience"
                                          value="<?= htmlspecialchars($event['target_audience']) ?>"
                                          required maxlength="150">
                            </div>

                            <div class="col-md-6">
                                   <label class="form-label" for="budget">Budget (RM)</label>
                                   <input class="form-control" id="budget" type="number"
                                          name="budget"
                                          value="<?= htmlspecialchars($event['budget']) ?>"
                                          min="0" step="0.01" required>
                            </div>

                            <div class="col-md-6">
                                   <label class="form-label" for="status">Status</label>
                                   <select class="form-select" id="status" name="status">
                                          <option value="Pending" <?= ($event['status'] ?? 'Pending') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                          <option value="Approved" <?= ($event['status'] ?? '') === 'Approved' ? 'selected' : '' ?>>Approved</option>
                                          <option value="Rejected" <?= ($event['status'] ?? '') === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                   </select>
                            </div>

                            <div class="col-12">
                                   <label class="form-label" for="description">Description</label>
                                   <textarea class="form-control" id="description"
                                          name="description" rows="4"><?= htmlspecialchars(
                                                 $event['description'] ?? ''
                                          ) ?></textarea>
                            </div>

                            <div class="col-12 d-flex justify-content-end pt-2">
                                   <button class="btn btn-primary px-4" type="submit">
                                          Update Event
                                   </button>
                            </div>
                     </div>
              </form>
       </div>
</div>

<?php require "app/includes/footer.php"; ?>