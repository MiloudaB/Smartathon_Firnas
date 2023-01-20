<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css"
     integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14="
     crossorigin=""/>
     <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous"
    ></script>
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
     integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg="
     crossorigin=""></script>

</head>
<body>
    <secition class="container-fluid">
        <div class="row mt-0 pt-0 w-100" style="background:#f8fffe;">
            <div class="col-3 control align-items-center pr-1">
                <div class="logo pb-5">
                    <img src="http://pocf.letsvers.com/assets/img/firnas-aero-logo.png" class="img-fluid"/>
                </div>
                <div class="card">
                    <div class="card-body">
                        <img id="preview" class="img-fluid img-thumbnail mb-2">
                        <input type="file" class="d-none" id="customFile">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="imageUpload">
                            <label class="custom-file-label" for="customFile"> Choose file</label>
                        </div>
                        <button class="btn btn-secondary mt-2 btn-block" onclick="postData()">Run infrence</button>
                    </div>
                </div>
                <img src="http://pocf.letsvers.com/assets/img/amana-logo.png" class="img-fluid w-50 mt-5"/>
            </div>
            <div class="col-9">
                <div class="row mb-2  content-row" style="display:none;" id="infer">
                    <div class="col-6 ">
                       <div class="card card-default  h-100">
                            <div class="card-header">JSON</div>
                            <div class="card-body p-0 d-flex justify-content-center align-items-center" style="white-space:break-spaces; font-size:13px;">
                                <pre id="json"><img src="http://pocf.letsvers.com/assets/img/loading.gif" class="img-fluid"/></pre>
                            </div>
                        </div>    
                    </div>
                    <div class="col-6">
                        <div class="card h-100">
                            <div class="card-header">IMAGE</div>
                            <div class="card-body p-0 d-flex justify-content-center align-items-center"><img id="inferedImg" class="img-fluid  justify-content-center align-self-center" src="http://pocf.letsvers.com/assets/img/loading.gif" alt=""></div>
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                       <div id="map">
                            <div id="map" style="height:400px;"></div>
                            <ul class="markers">
                                <li><a href="#"></a></li> 
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </secition>
    <style>
        .control {
            min-height: 100vh;
            background:#a7eadc12;
            border-right: 3px dashed #b9e5d1;
            display: flex;
            align-items: center !important;
            justify-content: center;
            flex-direction: column;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js" integrity="sha512-xsoiisGNT6Dw2Le1Cocn5305Uje1pOYeSzrpO3RD9K+JTpVH9KqSXksXqur8cobTEKJcFz0COYq4723mzo88/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        var map = L.map('map').setView([24.6998839, 46.8046069], 10);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' }).addTo(map);
            $.getJSON('{{ base_url("markers") }}', function(data) {
                const obj = JSON.parse(JSON.stringify(data));
                for (var i=0; i < obj.length; i++) {
                    const id = obj[i].id;
                        const lat = obj[i].lat;
                        const long = obj[i].lon;
                        L.marker([lat, long]).addTo(map).on('click', function () {
                            showTicket(id);
                        });
                    }
      
            });
        var fileInput = document.getElementById("imageUpload");
        var preview = document.getElementById("preview");
        fileInput.addEventListener("change", function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function(event) {
                var img = new Image();
                img.src = event.target.result;
                var base64 = reader.result.substring(reader.result.indexOf(",")+1);
                img.onload = function() {
                    EXIF.getData(img, function() {
                        preview.src = event.target.result;
                        var GPSLatitude = EXIF.getTag(this, "GPSLatitude");
                        var GPSLongitude = EXIF.getTag(this, "GPSLongitude");
                        var date = EXIF.getTag(this, "DateTimeOriginal");
                        var formData = new FormData()
                        formData.append('lat', GPSLatitude)
                        formData.append('lon', GPSLongitude)

                        $.ajax({
                            url: "{{base_url()}}insert", 
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                console.log(data);
                            }
                        });
                    });
                };
            };
            reader.readAsDataURL(file);
        });
        function postData() {
            var base64 = $('#preview').attr('src');
            if (base64 === "" || !base64) {
                alert('please insert a picture');
            }else {
                $("#infer").show();
                base64 = base64.replace('data:image/png;base64,', '')
                                                .replace('data:image/jpeg;base64,', '')
                                                .replace('data:image/gif;base64,', '');
                
                $.post('{{base_url()}}contactApi',{image:base64},function(data) {
                    data = JSON.parse(data);
                    document.getElementById('inferedImg').src = 'data:image/png;base64,'+data.image.b64+'';
                    document.getElementById("json").textContent = JSON.stringify(data.predictions, undefined, 2);

                });
            }
        }
    </script>

</body>
</html>