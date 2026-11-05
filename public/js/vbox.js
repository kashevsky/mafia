

var venoboxExports = requireVenobox();
const VenoBox = /*@__PURE__*/getDefaultExportFromCjs(venoboxExports);

const vboxOptions = {
  overlayColor: "#00000066",
  bgcolor: null,
  spinner: "grid",
  onContentLoadedEvent: new Event("vBoxContentLoaded", { bubbles: true }),
  onContentLoaded: (newcontent) => {
    document.dispatchEvent(vboxOptions.onContentLoadedEvent);
    let scripts = [];
    let startText = newcontent;
    function getScript() {
      let newArr = startText.split('<script>');
      if (newArr[1]) {
        let scriptSplit = newArr[1].split('</script>');
        scripts.push(scriptSplit[0]);
        let newArr2 = [
          newArr[0],
          scriptSplit[1],
          ...newArr.slice(2, newArr[length - 1]).map((el) => '<script>' + el),
        ];
        startText = newArr2.join('');
        getScript();
      }
    }
    getScript();
    let body = document.querySelector('body');
    if (scripts.length) {     
      scripts.forEach((script) => {
        let newScript = document.createElement('script');
        newScript.setAttribute('data-v-script', '');
        newScript.textContent = script;       
        body.appendChild(newScript);
      });
    }


  },
  onPreOpen: () => {
    deleteScript();
    vBox.isOpen = true;
  },
  onPreClose: () => {   
    vBox.isOpen = false;
    deleteScript();
  },
};

const vBox = new VenoBox(vboxOptions);

(window).vBox = vBox;
(window).openVBox = openVBox;

function openVBox(src, vbtype) {
  const link = document.createElement("a");
  link.href = src;
  link.setAttribute("data-vbtype", vbtype ? vbtype : "ajax");
  (link).settings = vBox.settings;

  vBox.close();
  setTimeout(() => {
    vBox.open(link);
  }, 500);
}

function deleteScript() {
  document.querySelectorAll('script[data-v-script]').forEach((el) => el.remove());
}

function vBoxHandler(e, target) {
  const closePopup = target.closest("[data-vclose]");
  const vBoxLink = target.closest("[data-vbox]");

    if (vBoxLink) {
      let href = vBoxLink.getAttribute('data-href');
      if (!href) {
        href = vBoxLink.getAttribute('href');
      }

      if (href) {
        e.preventDefault();
        if (!vBoxLink.settings) {
          vBoxLink.settings = vBox.settings;
        }
        vBox.close();
        setTimeout(() => {
          vBox.open(vBoxLink);
        }, 100);
      }
    }

  if (closePopup) {
    vBox.close();
  }
}

console.log("vbox.js loaded");

  document.addEventListener("click", (e) => {
    const target = e.target;
    vBoxHandler(e, target);
  });

