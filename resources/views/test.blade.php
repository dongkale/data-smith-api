@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <input id="file" type="file" class="form-control" name="file" style="height:auto;" required />            
        </div>        
        <div class="col-md-1">
            <button class="btn btn-primary" onclick="uploadFile()">Upload</button>
        </div>
    </div>
</div>

<script>

$(document).ready(function() {
    $('#file').on('change', function() {
        var file = $(this)[0].files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = $('<img>').attr('src', e.target.result);
            $('#preview').html(img);
        }
        reader.readAsDataURL(file);
    });
});

function uploadFile() {
    var file = $('#file')[0].files[0];
    var formData = new FormData();
    formData.append('file', file);
    $.ajax({
        url: '/api/fileUpload',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(data) {
            console.log(data);
        }
    });
}

</script>
@endsection
