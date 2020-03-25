<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                  <i class="fas fa-table mr-1"></i>Video List
                </div>
                <div class="col-md-2 float-right">
                  <a href="/user/add-video/"><button class="btn btn-primary btn-sm btn-block">Add Video</button></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Short</th>
                            <th>Filename</th>
                            <th>File ID</th>
                            <th>View</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($data as $k) :
                        ?>
                        <tr>
                            <td><?= $k["id"] ?></td>
                            <td><?= $k["shortlink"] ?></td>
                            <td><?= $k["filename"] ?></td>
                            <td><?= $k["drive_id"] ?></td>
                            <td><a href="/embed/<?= $k["shortlink"] ?>" target="blank"><button class="btn btn-primary btn-sm">View</button></a></td>
                            <td><a href="/user/delete-video/<?= $k["id"] ?>"><button class="btn btn-danger btn-sm">Delete</button></a></td>
                        </tr>
                        <?php 
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>