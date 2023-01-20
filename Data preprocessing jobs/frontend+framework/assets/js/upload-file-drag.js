const dropArea = document.querySelector('.drag-area');
const dragText = document.querySelector('.header');
const base_url = "http://localhost/fernas_ci";
let button = dropArea.querySelector('.button');
let input = dropArea.querySelector('input');

let file;

button.onclick = () => {
  input.click();
};

// when browse
input.addEventListener('change', function () {
  file = this.files[0];
  dropArea.classList.add('active');
  displayFile();
});

// when file is inside drag area
dropArea.addEventListener('dragover', (event) => {
  event.preventDefault();
  dropArea.classList.add('active');
  dragText.textContent = 'Release to Upload';
  // console.log('File is inside the drag area');
});

// when file leave the drag area
dropArea.addEventListener('dragleave', () => {
  dropArea.classList.remove('active');
  // console.log('File left the drag area');
  dragText.textContent = 'Drag & Drop';
});

// when file is dropped
dropArea.addEventListener('drop', (event) => {
  event.preventDefault();
  // console.log('File is dropped in drag area');

  file = event.dataTransfer.files[0]; // grab single file even of user selects multiple files
  // console.log(file);
  displayFile();
});

function displayFile() {
  const fsize = file.size;
  const filesize = Math.round((fsize / 1024));
  if (filesize > 2048) {
    alert("File too Big, please select a file less than less than 2mb");
    location.reload();
  }
  let fileType = file.type;
  // console.log(fileType);

  let validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];

  if (validExtensions.includes(fileType)) {
    // console.log('This is an image file');
    let fileReader = new FileReader();

    fileReader.onload = () => {
      let fileURL = fileReader.result;
      // console.log(fileURL);
      let imgTag = `<img src="${fileURL}" alt="">`;
      dropArea.innerHTML = imgTag;
    };
    fileReader.readAsDataURL(file);
    (async() => {
    const tags = await ExifReader.load(file);
    const imageDate = tags['DateTimeOriginal'].description;
    const unprocessedTagValue = tags['DateTimeOriginal'].value;
    var GPSLatitude = tags['GPSLatitude'].description;
    var GPSLongitude =tags['GPSLongitude'].description;
    var dateTime = tags['DateTimeOriginal'].value;
    var stroke = $(".stroke :selected").val();
    //var output = document.querySelector('input[name="output"]:checked').value;
    var date = parseDate(dateTime);
    var formData = new FormData()
    formData.append('GPSLatitude', GPSLatitude)
    formData.append('GPSLongitude', GPSLongitude)
    formData.append('TimeStamp', date)
    formData.append('stroke', stroke)
    formData.append('file', fileReader.result)

    $.ajax({
        url: base_url+"/insert", 
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            console.log(data);
        }
    });
    })()
  } else {
    alert('This is not an Image File');
    dropArea.classList.remove('active');
  }
}
function parseDate(s) {
  s = s.toString();
  var str = s.split(" ");
  //get date part and replace ':' with '-'
  var dateStr = str[0].replace(/:/g, "-");
  //concat the strings (date and time part)
  var properDateStr = dateStr + " " + str[1];
  //pass to Date
  var date = new Date(properDateStr);
  return date.getTime();
}