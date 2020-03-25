<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Add Video</h3></div>
                <div class="card-body">
                    <?php 
                        if($msg = $this->session->flashdata("msg")) :
                    ?>
                        <div class="alert alert-danger"><?= $msg ?></div>
                    <?php
                        endif;
                    ?>
                    <form method="post">
                        <div class="form-group"><label class="small mb-1" for="inputPassword">Video ID (Google Drive ID)</label><input class="form-control py-4" id="inputPassword" type="text" name="vid" placeholder="Enter Video ID" /></div>
                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0"><button class="btn btn-primary" type="submit">Add</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>