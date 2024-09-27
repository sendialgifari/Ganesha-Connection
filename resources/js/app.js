import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";
import "flowbite";
import "./bootstrap";
import "../css/star-ratings.css";
import "../css/app.css";
import "@protonemedia/laravel-splade/dist/style.css";
import "@protonemedia/laravel-splade/dist/jodit.css";

import VueApexCharts from "vue3-apexcharts";

import { createApp } from "vue/dist/vue.esm-bundler.js";
import { renderSpladeApp, SpladePlugin } from "@protonemedia/laravel-splade";

const el = document.getElementById("app");

createApp({
  render: renderSpladeApp({ el }),
})
  .use(VueApexCharts)
  .use(SpladePlugin, {
    max_keep_alive: 10,
    transform_anchors: false,
    progress_bar: {
      delay:250,
      color:"#ffa800",
      css:true,
      spinner:false
    },
    components: {
      Carousel,
      CarouselSlide: Slide,
      CarouselPagination: Pagination,
      CarouselNavigation: Navigation,
    },
  })
  .mount(el);
