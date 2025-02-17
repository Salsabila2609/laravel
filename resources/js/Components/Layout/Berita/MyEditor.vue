<template>
  <div>
    <div id="editorjs"></div>
    <button @click="saveEditorData" class="save-button">Save News</button>
  </div>
</template>

<script>
import { onMounted, ref } from "vue";
import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import List from "@editorjs/list";
import CodeTool from "@editorjs/code";
import EditorJsHtml from "editorjs-html";
import ImageTool from "@editorjs/image";
import QuoteTool from "@editorjs/quote";
import TableTool from "@editorjs/table";
import Embed from "@editorjs/embed";
import LinkTool from "@editorjs/link"; 
import axios from "axios";

export default {
  props: {
    modelValue: {
      type: String,
      default: "",
    },
  },
  setup(props, { emit }) {
    const editor = ref(null);
    const editorJsHtml = EditorJsHtml();

    // Function to convert HTML to blocks for Editor.js
    const convertHtmlToEditorBlocks = (htmlContent) => {
      console.log("HTML Content before conversion:", htmlContent);

      const blocks = [];
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = htmlContent;

      tempDiv.childNodes.forEach((node) => {
        if (node.nodeName === "P") {
          blocks.push({
            type: "paragraph",
            data: {
              text: node.innerHTML,
            },
          });
        } else if (node.nodeName === "H1" || node.nodeName === "H2") {
          blocks.push({
            type: "header",
            data: {
              text: node.innerText,
              level: node.nodeName === "H1" ? 1 : 2,
            },
          });
        } else if (node.nodeName === "UL") {
          blocks.push({
            type: "list",
            data: {
              style: "unordered",
              items: Array.from(node.children).map((li) => li.innerText),
            },
          });
        } else if (node.nodeName === "IMG") {
          blocks.push({
            type: "image",
            data: {
              file: {
                url: node.src,
              },
              caption: node.alt || "",
            },
          });
        } else if (node.nodeName === "IFRAME") {
          console.log("Found iframe node: ", node);
          blocks.push({
            type: "embed",
            data: {
              embed: node.src,
              service: "youtube", // Opsional, tergantung service
            },
          });
        } else if (node.nodeName === "A") {
          // Handle standalone links (though these are usually inside paragraphs)
          blocks.push({
            type: "paragraph",
            data: {
              text: node.outerHTML
            }
          });
        }
      });

      console.log("Editor.js Blocks after conversion:", blocks);
      return blocks;
    };

    // Function to save editor data
    const saveEditorData = async () => {
      if (editor.value) {
        const data = await editor.value.save();

        // Tambahkan check untuk blok kosong
        data.blocks.forEach((block) => {
          if (block.type === "paragraph" && !block.data.text.trim()) {
            block.data.text = "<br>";
          }
        });

        const htmlContent = editorJsHtml.parse(data);
        emit("update:modelValue", htmlContent);
      }
    };

    // Initialize Editor.js on mounted
    onMounted(() => {
      try {
        // Convert HTML to blocks for Editor.js
        const initialBlocks = convertHtmlToEditorBlocks(props.modelValue);

        editor.value = new EditorJS({
          holder: "editorjs",
          tools: {
            header: Header,
            list: List,
            code: CodeTool,
            image: {
              class: ImageTool,
              config: {
                uploader: {
                  uploadByFile(file) {
                    const formData = new FormData();
                    formData.append("image", file);

                    return axios
                      .post("/upload-image", formData, {
                        headers: {
                          "Content-Type": "multipart/form-data",
                        },
                      })
                      .then((response) => {
                        if (response.data && response.data.url) {
                          return {
                            success: 1,
                            file: {
                              url: response.data.url,
                            },
                          };
                        }
                        return {
                          success: 0,
                          message: "Failed to upload image.",
                        };
                      })
                      .catch((error) => {
                        console.error("Upload error: ", error);
                        return {
                          success: 0,
                          message: "Upload failed",
                        };
                      });
                  },
                },
              },
            },
            quote: QuoteTool,
            table: TableTool,
            embed: {
              class: Embed,
              config: {
                services: {
                  youtube: true,
                  vimeo: true,
                  instagram: true,
                  twitter: true,
                },
              },
            },
          },
          data: {
            blocks: initialBlocks, // Pass the initial blocks data
          },
          inlineToolbar: true,  // Enables inline toolbar for bold, italic, and underline
        });
      } catch (error) {
        console.error("Error initializing EditorJS:", error);
      }
    });

    return {
      editor,
      saveEditorData,
    };
  },
};
</script>

<style scoped>
  #editorjs a {
    color: blue; /* Menentukan warna biru untuk link */
    text-decoration: underline; /* Menambahkan garis bawah pada link */
  }

#editorjs {
  
  min-height: 300px;
  border: 1px solid #ccc;
  padding: 15px;
  border-radius: 4px;
  background-color: #f9f9f9;
}

/* Tambahkan gaya untuk tombol Save */
  .save-button {
    background-color: #396C6D; /* Latar belakang hijau */
    color: white;            /* Warna teks putih */
    padding: 10px 20px;      /* Padding tombol */
    border: none;            /* Hapus border default */
    border-radius: 4px;      /* Radius border */
    cursor: pointer;         /* Ubah kursor menjadi pointer */
    
    /* Menempatkan tombol di kanan bawah layar */
    position: fixed;
    bottom: 50px;
    right: 50px;
  }

  .save-button:hover {
    background-color: #99CBC0; /* Ubah warna saat hover */
  }
</style>
