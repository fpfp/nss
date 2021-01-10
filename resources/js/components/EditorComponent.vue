<template>
  <div class="editor container-fluid">
    <div class="row">
      <div class="col-7">
        <div class="canvas-container">
          <canvas ref="canvas" width="600" height="600"></canvas>
          <div class="text-center">
            <a class="btn btn-outline-secondary" @click="toggleDrawingArea()"
              >Toggle Drawing Area</a
            >
          </div>
        </div>
      </div>
      <div class="col-5">
        <div class="sidebar mt-4">
          <div class="row">
            <div class="col-12">
              <input
                class="form-control form-control-lg form-control-plaintext"
                type="text"
                name="name"
                v-model="name"
                id="name"
                placeholder="untitled design"
                v-bind:class="{ 'is-invalid': error.name }"
              />
              <div class="invalid-feedback">
                Name required. Min length 6 chars
              </div>
            </div>
          </div>

          <div v-if="graphics" class="row graphics">
            <div class="col-12 mt-4">Choose our graphics:</div>
            <div class="col-12 mt-2">
              <div
                class="graphic"
                v-for="(graphic, index) in graphics"
                :key="index"
              >
                <a
                  class="graphic-item"
                  :title="graphic.name"
                  @click="addGraphicToTshirt(graphic)"
                  ><img
                    class="img-fluid"
                    :src="graphic.path"
                    :alt="graphic.name"
                /></a>
              </div>
            </div>
            <div class="col-12 text-danger" v-if="error.graphic">
              Please insert at least one graphic
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-12">
              <button
                type="button"
                class="btn btn-lg btn-success"
                @click="saveDesign()"
              >
                Save current Design
              </button>

              <button
                type="button"
                class="btn btn-lg btn-secondary"
                @click="cancelDesign()"
              >
                Clear
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { fabric } from "fabric";

export default {
  created() {
    //console.log("Component Design Editor created.");
  },
  mounted() {
    this.loadTshirt(this.$route.params.tshirtId);

    this.loadGraphics();

    //initialize fabric canvas
    this.canvas = new fabric.Canvas(this.$refs.canvas);

    this.clipPath = new fabric.Rect({
      width: this.canvas.height / 3,
      height: this.canvas.height / 2.7,
      top: this.canvas.height / 2.7,
      left: this.canvas.width / 3,
      absolutePositioned: true,
    });

    this.addDrawingArea();

    //prevent element goes outside canvas
    this.canvas.on("after:render", () => {
      const item = this.canvas.getActiveObject();
      if (item && !item.isOnScreen()) {
        item.center();
      }
    });

    //bind delete key to remove selected element on canvas
    document.addEventListener(
      "keydown",
      (ev) => {
        var keyCode = ev.keyCode;
        var focused = document.activeElement == document.body;

        if (focused && (keyCode == 46 || keyCode == 8)) {
          this.canvas.remove(this.canvas.getActiveObject());
        }
      },
      false
    );
  },
  computed: {
    user: function () {
      return this.$store.state.user;
    },
  },
  data() {
    return {
      name: "",
      error: {
        name: false,
        graphic: false,
      },
      canvas: {},
      clipPath: {},
      drawingArea: {},
      tshirt: {},
      designId: null,
      graphics: [],
    };
  },
  methods: {
    loadTshirt(tshirtId) {
      axios
        .get("/api/tshirts/" + tshirtId)
        .then((response) => {
          //console.log(response);
          if (response.data) {
            this.tshirt = response.data;
            this.addBackgroundTshirt(this.tshirt.full_image);
          }
        })
        .catch((error) => console.log(error)); //request fail;
    },
    loadGraphics() {
      axios
        .get("/api/graphics")
        .then((response) => {
          //console.log(response);
          if (response.data) {
            this.graphics = response.data;
          }
        })
        .catch((error) => console.log(error)); //request fail;
    },
    addBackgroundTshirt(imgPath) {
      console.log(imgPath);
      fabric.Image.fromURL(imgPath, (img) => {
        img.set({
          left: 0,
          top: 0,
        });
        img.selectable = false;
        img.evented = false;
        img.hoverCursor = "pointer";
        img.id = "tshirt";
        img.scaleToHeight(this.canvas.width);

        this.canvas.add(img);
        this.canvas.sendToBack(img);
      });
    },
    addGraphicToTshirt(graphic) {
      fabric.loadSVGFromURL(graphic.path, (objects, options) => {
        let obj = fabric.util.groupSVGElements(objects, options);
        obj.scaleToWidth(this.clipPath.width);
        obj.clipPath = this.clipPath;
        this.canvas.centerObject(obj);

        this.canvas.add(obj).renderAll();
      });
    },
    deselectCanvas() {
      this.canvas.discardActiveObject().renderAll();
    },
    addDrawingArea() {
      this.drawingArea = new fabric.Rect({
        width: this.canvas.height / 3,
        height: this.canvas.height / 2.7,
        top: this.canvas.height / 2.7,
        left: this.canvas.width / 3,
        absolutePositioned: true,
        stroke: "#666",
        strokeWidth: 1,
        strokeDashArray: [10, 10],
        fill: "rgba(0,0,0,0)",
        selectable: false,
        evented: false,
        hoverCursor: "pointer",
        id: "drawingArea",
      });

      this.canvas.add(this.drawingArea);
    },
    toggleDrawingArea(status) {
      this.canvas.getObjects().forEach((el) => {
        if (el.id === "drawingArea") {
          if (typeof status !== "undefined") {
            el.visible = status;
          } else {
            el.visible = !el.visible;
          }
        }
      });
      this.canvas.renderAll();
    },
    generateImageFromCanvas() {
      this.toggleDrawingArea(false);

      this.canvas.renderAll();
      const dataURL = this.canvas.toDataURL({
        width: this.canvas.width,
        height: this.canvas.height,
        left: 0,
        top: 0,
        format: "png",
      });
      return dataURL;
    },
    validateForm: function (e) {
      let elementOnCanvas = false;
      this.canvas.getObjects().forEach((el) => {
        if (el.id !== "drawingArea" && el.id !== "tshirt") {
          elementOnCanvas = true;
        }
      });
      this.error.graphic = !elementOnCanvas;

      if (!this.name || this.name.length < 6) {
        this.error.name = true;
        document.getElementById("name").focus();
        return false;
      } else {
        this.error.name = false;
        return true;
      }
    },
    saveDesign() {
      if (this.validateForm()) {
        axios
          .post("/api/designs" + (this.designId ? "/" + this.designId : ""), {
            name: this.name,
            b64img: this.generateImageFromCanvas(),
            _method: this.designId ? "PUT" : "POST",
            tshirt_id: this.tshirt.id,
            status: 1,
          })
          .then((response) => {
            if (response.data) {
              Toast.fire({
                icon: "success",
                title:
                  "Design " +
                  (this.designId ? "updated" : "saved") +
                  " successfully",
              });

              this.designId = response.data.id;
            }
          })
          .catch((error) => console.log(error)); //request fail;
      }
    },
    cancelDesign() {
      this.canvas.clear();
      this.addBackgroundTshirt(this.tshirt.full_image);
      this.addDrawingArea();
    },
  },
};
</script>
