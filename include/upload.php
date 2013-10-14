
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>

<div class="panel panel-primary">
	<div class="panel-heading"><h3 class="panel-title">File Management</h3></div>
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="server/php/?id=<?php print $_GET['id']?>" method="POST" enctype="multipart/form-data">
        <br>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
       
       <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                &nbsp;
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <!-- The loading indicator is shown during file processing -->
                <span class="fileupload-loading"></span>
            </div>
            <!-- The global progress information -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
       
       
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
    <br>
 </div>


<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        
        <td>
            <p class="name">{%=file.name%}</p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <p class="size">{%=o.formatFileSize(file.size)%}</p>
            {% if (!o.files.error) { %}
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            {% } %}
        </td>
        <td>
            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" target='_blank'>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
			                
			          <!-- Button to trigger modal -->
			<a href="#myModal{%=file.id%}" role="button" class="btn btn-default btn-danger" data-toggle="modal"><i class="glyphicon glyphicontrash glyphicon-white>"></i>Delete</a>
						
			<!-- Modal -->
			<div id="myModal{%=file.id%}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
			      <div class="modal-content">
					<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">Confirm Deletion</h3>
					</div>
					<div class="modal-body">
					<p>Are you sure you want to delete?</p>
					<p>Deletion cannot be undone</p>
					</div>
					<div class="modal-footer">
					<div class="pull-left"><button data-dismiss="modal" class="btn btn-default btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
					            <i class="glyphicon glyphicontrash glyphicon-white>"></i>
					            <span>Delete</span>
					        </button></div>
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">No</button>
					</div>
			  </div><!-- /.modal-content -->
			 </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- End Modal -->
			
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="/js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>
</div>
