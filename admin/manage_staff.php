<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
checkRole(['admin']);

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_staff'])) {
        $name = sanitize($conn, $_POST['name']);
        $username = sanitize($conn, $_POST['username']);
        $email = sanitize($conn, $_POST['email']);
        $phone = sanitize($conn, $_POST['phone']);
        $gender = sanitize($conn, $_POST['gender']);
        $dob = sanitize($conn, $_POST['dob']);
        $age = intval($_POST['age']);
        $address = sanitize($conn, $_POST['address']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = 'staff';
        
        $sql = "INSERT INTO users (name, username, email, phone, gender, dob, age, address, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssisss", $name, $username, $email, $phone, $gender, $dob, $age, $address, $password, $role);
        
        try {
            if ($stmt->execute()) {
                setFlashMessage('success', 'Staff added successfully.');
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
             if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                setFlashMessage('error', 'Error: Username or Email already exists.');
            } else {
                setFlashMessage('error', 'Error adding staff: ' . $e->getMessage());
            }
        }
    } 
    elseif (isset($_POST['edit_staff'])) {
        $id = intval($_POST['user_id']);
        $name = sanitize($conn, $_POST['name']);
        $username = sanitize($conn, $_POST['username']);
        $email = sanitize($conn, $_POST['email']);
        $phone = sanitize($conn, $_POST['phone']);
        $gender = sanitize($conn, $_POST['gender']);
        $dob = sanitize($conn, $_POST['dob']);
        $age = intval($_POST['age']);
        $address = sanitize($conn, $_POST['address']);
        
        $sql = "UPDATE users SET name=?, username=?, email=?, phone=?, gender=?, dob=?, age=?, address=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssisi", $name, $username, $email, $phone, $gender, $dob, $age, $address, $id);
        
        if ($stmt->execute()) {
             // Update password if provided
             if (!empty($_POST['password'])) {
                 $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                 $conn->query("UPDATE users SET password='$pass' WHERE user_id=$id");
             }
             setFlashMessage('success', 'Staff details updated.');
        } else {
             setFlashMessage('error', 'Error updating staff.');
        }
    }
    elseif (isset($_POST['toggle_status'])) {
        $id = intval($_POST['user_id']);
        $status = $_POST['status'] == 'active' ? 'inactive' : 'active';
        $conn->query("UPDATE users SET status='$status' WHERE user_id=$id");
        setFlashMessage('success', 'Staff status updated.');
    }
    elseif (isset($_POST['delete_staff'])) {
        $id = intval($_POST['user_id']);
        $conn->query("DELETE FROM users WHERE user_id=$id");
        setFlashMessage('success', 'Staff deleted successfully.');
    }
    redirect('admin/manage_staff.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold mb-4">Staff Management</h2>
            
            <?php if(function_exists('displayFlashMessage')) displayFlashMessage(); ?>

            <div class="mb-4 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                    <i class="fas fa-plus"></i> Add New Staff
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Info</th>
                                    <th>Contact</th>
                                    <th>Role Data</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC");
                                if ($res->num_rows > 0):
                                    while($row = $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>#<?php echo $row['user_id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['gender'] ?? '-'); ?>, <?php echo $row['age'] ?? '-'; ?> yrs</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-1"></i> <?php echo htmlspecialchars($row['email']); ?><br>
                                        <i class="fas fa-phone text-muted me-1"></i> <?php echo htmlspecialchars($row['phone']); ?>
                                    </td>
                                    <td>
                                        <small>User: <strong><?php echo htmlspecialchars($row['username']); ?></strong></small><br>
                                        <small>Joined: <?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editStaffModal<?php echo $row['user_id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                <input type="hidden" name="delete_staff" value="1">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Change status for this staff member?');">
                                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
                                                <input type="hidden" name="toggle_status" value="1">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Toggle Status">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal per row -->
                                <div class="modal fade" id="editStaffModal<?php echo $row['user_id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <form method="POST" class="modal-content">
                                            <input type="hidden" name="edit_staff" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Staff: <?php echo htmlspecialchars($row['name']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Full Name</label>
                                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Username</label>
                                                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Gender</label>
                                                        <select name="gender" class="form-select">
                                                            <option value="Male" <?php echo $row['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                                            <option value="Female" <?php echo $row['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                                            <option value="Other" <?php echo $row['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Date of Birth</label>
                                                        <input type="date" name="dob" class="form-control" value="<?php echo $row['dob']; ?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Age</label>
                                                        <input type="number" name="age" class="form-control" value="<?php echo $row['age']; ?>">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Address</label>
                                                        <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($row['address']); ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">New Password (leave blank to keep current)</label>
                                                        <input type="password" name="password" class="form-control" minlength="6">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php endwhile; else: ?>
                                    <tr><td colspan="6" class="text-center">No staff found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" class="modal-content">
            <input type="hidden" name="add_staff" value="1">
            <div class="modal-header">
                <h5 class="modal-title">Add New Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Initial Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
