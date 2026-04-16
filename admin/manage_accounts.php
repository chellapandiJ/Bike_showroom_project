<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// Handle Status Toggle
if (isset($_POST['toggle_status'])) {
    $id = intval($_POST['user_id']);
    $status = $_POST['status'] == 'active' ? 'inactive' : 'active';
    $conn->query("UPDATE users SET status='$status' WHERE user_id=$id");
    setFlashMessage('success', 'User status updated.');
    redirect('manage_accounts.php');
}

// Handle Delete
if (isset($_POST['delete_user'])) {
    $id = intval($_POST['user_id']);
    // Don't delete self
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE user_id=$id");
        setFlashMessage('success', 'User deleted successfully.');
    } else {
        setFlashMessage('error', 'You cannot delete your own account.');
    }
    redirect('manage_accounts.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Accounts - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">User Accounts</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
                                while($row = $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>#<?php echo $row['user_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><span class="badge bg-info text-dark"><?php echo ucfirst($row['role']); ?></span></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($row['user_id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
                                                <input type="hidden" name="toggle_status" value="1">
                                                <button type="submit" class="btn btn-sm btn-outline-warning">Toggle Status</button>
                                            </form>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                <input type="hidden" name="delete_user" value="1">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">Current User</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
