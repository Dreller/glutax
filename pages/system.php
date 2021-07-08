<?php
# Global Include.
include('../php/gtInclude.php');
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?php echo _OPTION_SYSTEM; ?></h1>
<p class="lead text-white-50">Advanced System Administrator Tools</p>
<hr>

<div class="bg-light p-3 rounded shadow-sm">
    <!-- Quick Admin Actions --> 
    <div class="btn-group" role="group" aria-label="Quick System Actions">
        <button type="button" class="btn btn-outline-primary" data-bs-target="#systemUpdate" data-bs-toggle="modal">System Update</button>
        <button type="button" class="btn btn-outline-secondary">Future use</button>
    </div>
</div>

<!-- MODAL: SYSTEM UPDATE TOOL --> 
<div class="modal fade" id="systemUpdate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">System Update</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="X"></button>
            </div>
            <div class="modal-body text-start">
                Current version: <strong><?php echo $appVersion; ?></strong><br>
                Available version: <?php  
                        $options = Array(
                            CURLOPT_URL => "https://api.github.com/repos/dreller/glutax/releases/latest",
                            CURLOPT_HEADER => "Accept: application/vnd.github.v3+json",
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
                        );
                        $ch = curl_init();
                        curl_setopt_array($ch, $options);
                        $output = curl_exec($ch);
                        curl_close($ch);
                        
                        $response = json_decode($output, TRUE);
                        if( isset($response['message']) ){
                            $msg = $response['message'];
                            if( $msg == "Not Found" ){
                                $msg = "No version is currently published.";
                            }
                            echo "<strong>$msg</strong>";
                        }
                    ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-target="#systemUpdateProcessing" data-bs-toggle="modal" data-bs-dismiss="modal">Start Update</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="systemUpdateProcessing">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">System Update Processing</h5>
            </div>
            <div class="modal-body">
                Update in progress, please wait...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>