<?php namespace Poki; ?>

<div class="col-md-12 col-lg-12 col-xl-12 align-self-center">
    <div class="card bg-white m-b-30">
        <div class="card-body new-user">
            <?php if ($admin->role == 'admin'): ?>
            <a href="<?= Routes::find('users-create') ?>" class="btn btn-outline-info btn-animation float-right">
                <span class="mdi mdi-plus"></span> Add a new user
            </a>
            <?php endif ?>
            <h5 class="header-title mb-4 mt-0">Users Profiles</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="border-top-0" style="width:60px;">Member</th>
                            <th class="border-top-0">Name</th>
                            <th class="border-top-0">Email</th>
                            <th class="border-top-0">Role</th>
                            <th class="border-top-0 text-center">Active</th>
                            <th class="border-top-0 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $k => $user): ?>
                        <tr id="<?= md5($user->id) ?>">
                            <td>
                                <img class="rounded-circle" src="<?= THEME ?>assets/images/users/avatar-1.jpg" alt="user" width="40">
                            </td>
                            <td><?= $user->name ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->role ?></td>
                            <td align="center">
                                <?php if ($admin->roleid=='1'): ?>
                                <div class="btn-group btn-group-toggle activetoggle" data-toggle="buttons" id="active-<?= md5($user->id) ?>">
                                    <label for="activeradio1" class="btn btn-light <?= $user->active=='1' ? 'active':'' ?>">
                                        <input type="radio" name="active" id="activeradio1" value="1" <?= $user->active=='1' ? 'checked=""':'' ?>>Active
                                    </label>
                                    <label for="activeradio2" class="btn btn-light <?= $user->active=='0' ? 'active':'' ?>">
                                        <input type="radio" name="active" id="activeradio2" value="0" <?= $user->active=='0' ? 'checked=""':'' ?>>Inactive
                                    </label>
                                </div>
                                <?php else: ?>
                                    <?= $user->active== '1' ? 'Yes' : 'No' ?>
                                <?php endif ?>
                            </td>
                            <td class="text-right">
                                <?php if ($admin->roleid=='1'): ?>
                                    <a href="<?= Routes::find('users-update') .'/'. md5($user->id) ?>" class="btn btn-info btn-sm"><i class="mdi mdi-pencil"></i></a>
                                    <a href="#" data-rm="<?= md5($user->id) ?>" class="btn btn-danger btn-sm deluser"><i class="mdi mdi-delete"></i></a>
                                <?php else: ?>
                                    No possible action.
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>