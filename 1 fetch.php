<div class="container">
    <h2>User Records</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password (Hashed)</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['password']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No records found.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.html" class="btn btn-primary">Add New User</a>
    </div>
</div>
