import EmbedTool from "@editorjs/embed";

export default class CustomEmbed extends EmbedTool {
  static get toolbox() {
    return {
      title: "YouTube",
      icon: `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"></path>
          <path d="m10 15 5-3-5-3z"></path>
        </svg>
      `,
    };
  }

  render() {
    const container = document.createElement("div");

    if (!this.data || !this.data.source) {
      // Input URL jika data embed belum tersedia
      const input = document.createElement("input");
      input.classList.add("cdx-input");
      input.placeholder = "https://www.youtube.com/watch?v=w8vsuOXZBXc";
      input.type = "url";

      input.addEventListener("input", (event) => {
        const url = event.target.value;
        if (this.isValidYouTubeUrl(url)) {
          this.data = {
            service: "youtube",
            source: this.getEmbedUrl(url),
          };
          this.updateEmbed(container);
        }
      });

      container.appendChild(input);
      return container;
    }

    this.updateEmbed(container);
    return container;
  }

  updateEmbed(container) {
    container.innerHTML = ""; // Bersihkan container sebelum menambahkan iframe
    const iframeWrapper = document.createElement("div");
    iframeWrapper.style.position = "relative";
    iframeWrapper.style.paddingTop = "56.25%"; // Rasio aspek 16:9
    iframeWrapper.style.width = "100%";
    
    const iframe = document.createElement("iframe");
    iframe.src = this.data.source;
    iframe.style.position = "absolute";
    iframe.style.top = "0";
    iframe.style.left = "0";
    iframe.style.width = "100%";
    iframe.style.height = "100%";
    iframe.style.border = "0";
    iframe.allowFullscreen = true;
    
    iframeWrapper.appendChild(iframe);
    container.appendChild(iframeWrapper);
    
  }

  isValidYouTubeUrl(url) {
    const youtubeRegex = /^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/;
    return youtubeRegex.test(url);
  }

  getEmbedUrl(url) {
    const videoId = url.split("v=")[1]?.split("&")[0];
    return `https://www.youtube.com/embed/${videoId}`;
  }

  save(blockContent) {
    return this.data;
  }

  validate(savedData) {
    return !!(savedData.service && savedData.source);
  }
}
