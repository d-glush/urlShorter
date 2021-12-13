const loader = '<div class="preloader-wrapper big active">\n' +
    '    <div class="spinner-layer spinner-blue-only">\n' +
    '      <div class="circle-clipper left">\n' +
    '        <div class="circle"></div>\n' +
    '      </div><div class="gap-patch">\n' +
    '        <div class="circle"></div>\n' +
    '      </div><div class="circle-clipper right">\n' +
    '        <div class="circle"></div>\n' +
    '      </div>\n' +
    '    </div>\n' +
    '  </div>'

const showToaster = function() {
  M.toast({displayLength: 9999999, html: loader})
}

const hideAllToasters = function() {
  M.Toast.dismissAll();
}

const processGoClick = function() {
  const fullUrl = fullUrlInput.value;
  const customUrl = customUrlInput.value;
  showToaster();
  goButton.setAttribute('disabled', 'disabled');
  copyButton.setAttribute('disabled', 'disabled');

  const formData = new FormData();
  formData.append("url", fullUrl);
  formData.append("customShortUrl", customUrl);

  const xhr = new XMLHttpRequest();
  xhr.onload  = function() {
    hideAllToasters();
    goButton.removeAttribute('disabled');
    const result = JSON.parse(this.response);
    if (result.isError) {
      console.log(result)
      fullUrlInput.classList.remove('valid');
      customUrlInput.classList.remove('valid');
      fullUrlInput.classList.add('invalid');
      customUrlInput.classList.add('invalid');
      shortUrlOutput.value = '';
    } else {
      shortUrlOutput.value = `https://${document.domain}/` + result.shortUrl;
      copyButton.removeAttribute('disabled');
    }
  }
  xhr.open("POST", `https://${document.domain}/api/create_short_url`);
  xhr.send(formData);
}

const copyShortUrl = async function() {
  if (!shortUrlOutput.value) {
    return;
  }
  await navigator.clipboard.writeText(shortUrlOutput.value);
}

const goButton = document.getElementById('go_button');

const fullUrlInput = document.getElementById('full_url_input');
const customUrlInput = document.getElementById('custom_url_input');

const shortUrlOutput = document.getElementById('short_url_output');
const copyButton = document.getElementById('copy_button');

goButton.onclick = processGoClick;
copyButton.onclick = copyShortUrl;
