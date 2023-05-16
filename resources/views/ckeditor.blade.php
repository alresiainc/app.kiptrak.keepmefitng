<!DOCTYPE html>
<html>
<head>
    <title>Laravel CKEditor Image Upload Example - LaravelTuts.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .ck-editor__editable_inline {
        min-height: 300px;
    }
    </style>
</head>
<body>
    
<div class="container">
  
    <div class="text-center flex items-center justift-center my-5">
        <h1 class="">Laravel CKEditor Image Upload Example - LaravelTuts.com</h1>
    </div>
    
    <form>
        <div class="form-group mb-3">
            <label class="mb-1 font-semibold">Title:</label>
            <input type="text" name="title" class="form-control" placeholder="Title" value="{{ old('title') }}">
        </div>
  
        <div class="form-group mb-3">
            <label class="mb-1 font-semibold">Slug:</label>
            <input type="text" name="slug" class="form-control" placeholder="Slug" value="{{ old('slug') }}">
        </div>
  
        <div class="form-group mb-3">
            <label class="mb-1 font-semibold">Body:</label>
            <textarea name="editor" id="editor"></textarea>
        </div>
  
        <div class="form-group mb-3">
            <button class="btn btn-success mb-2" type="submit">Submit</button>
        </div>
    </form>
</div>
     
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ),{
            ckfinder: {
                uploadUrl: '{{route('createCkeditorPost').'?_token='.csrf_token()}}',
            }
        })
        .catch( error => {
              
        } );
</script>
</body>
</html>