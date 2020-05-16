/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!***********************!*\
  !*** ./src/blocks.js ***!
  \***********************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__noto_block_js__ = __webpack_require__(/*! ./noto/block.js */ 1);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__huiji_block_js__ = __webpack_require__(/*! ./huiji/block.js */ 4);\n/**\n * Gutenberg Blocks\n *\n * All blocks related JavaScript files should be imported here.\n * You can create a new block folder in this dir and include code\n * for that block here as well.\n *\n * All blocks should be included here since this is the file that\n * Webpack is compiling as the input file.\n */\n\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9ja3MuanM/N2I1YiJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEd1dGVuYmVyZyBCbG9ja3NcbiAqXG4gKiBBbGwgYmxvY2tzIHJlbGF0ZWQgSmF2YVNjcmlwdCBmaWxlcyBzaG91bGQgYmUgaW1wb3J0ZWQgaGVyZS5cbiAqIFlvdSBjYW4gY3JlYXRlIGEgbmV3IGJsb2NrIGZvbGRlciBpbiB0aGlzIGRpciBhbmQgaW5jbHVkZSBjb2RlXG4gKiBmb3IgdGhhdCBibG9jayBoZXJlIGFzIHdlbGwuXG4gKlxuICogQWxsIGJsb2NrcyBzaG91bGQgYmUgaW5jbHVkZWQgaGVyZSBzaW5jZSB0aGlzIGlzIHRoZSBmaWxlIHRoYXRcbiAqIFdlYnBhY2sgaXMgY29tcGlsaW5nIGFzIHRoZSBpbnB1dCBmaWxlLlxuICovXG5cbmltcG9ydCAnLi9ub3RvL2Jsb2NrLmpzJztcbmltcG9ydCAnLi9odWlqaS9ibG9jay5qcyc7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2tzLmpzXG4vLyBtb2R1bGUgaWQgPSAwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///0\n");

/***/ }),
/* 1 */
/*!***************************!*\
  !*** ./src/noto/block.js ***!
  \***************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss__ = __webpack_require__(/*! ./style.scss */ 2);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 3);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__editor_scss__);\n//  Import CSS.\n\n\n\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\nvar RichText = wp.blockEditor.RichText;\n\n\nregisterBlockType('tinyjpfont/noto', {\n    title: \"Noto Sans Japanese\",\n    icon: 'edit',\n    category: 'common',\n    example: {\n        attributes: {\n            cover: 'https://storage.googleapis.com/japanese-font-for-wordpress/huiji.png',\n            author: 'Hui/raspi0124',\n            pages: 500\n        }\n    },\n    attributes: {\n        textString: {\n            type: 'array',\n            source: 'children',\n            selector: 'p'\n        }\n    },\n\n    // props are passed to edit by default\n    // props contains things like setAttributes and attributes\n    edit: function edit(props) {\n\n        // we are peeling off the things we need\n        var setAttributes = props.setAttributes,\n            attributes = props.attributes;\n\n        // This function is called when RichText changes\n        // By default the new string is passed to the function\n        // not an event object like react normally would do\n\n        function onTextChange(changes) {\n            // works very much like setState\n            setAttributes({\n                textString: changes\n            });\n        }\n\n        return wp.element.createElement(RichText, {\n            tagName: 'p',\n            value: attributes.textString,\n            onChange: onTextChange,\n            className: 'wp-block-tinyjpfont-noto',\n            placeholder: 'Enter your favorite text with your fevorite font! yay!'\n        });\n    },\n\n\n    // again, props are automatically passed to save and edit\n    save: function save(props) {\n        var attributes = props.attributes;\n\n        // We want the text to be an h2 element\n        // and we place the textString value just\n        // like we would in a normal react app\n\n        return wp.element.createElement(\n            'p',\n            { 'class': 'wp-block-tinyjpfont-noto' },\n            attributes.textString\n        );\n    }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMS5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ub3RvL2Jsb2NrLmpzPzQ4YmIiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gIEltcG9ydCBDU1MuXG5pbXBvcnQgJy4vc3R5bGUuc2Nzcyc7XG5pbXBvcnQgJy4vZWRpdG9yLnNjc3MnO1xuXG52YXIgX18gPSB3cC5pMThuLl9fO1xudmFyIHJlZ2lzdGVyQmxvY2tUeXBlID0gd3AuYmxvY2tzLnJlZ2lzdGVyQmxvY2tUeXBlO1xudmFyIFJpY2hUZXh0ID0gd3AuYmxvY2tFZGl0b3IuUmljaFRleHQ7XG5cblxucmVnaXN0ZXJCbG9ja1R5cGUoJ3RpbnlqcGZvbnQvbm90bycsIHtcbiAgICB0aXRsZTogXCJOb3RvIFNhbnMgSmFwYW5lc2VcIixcbiAgICBpY29uOiAnZWRpdCcsXG4gICAgY2F0ZWdvcnk6ICdjb21tb24nLFxuICAgIGV4YW1wbGU6IHtcbiAgICAgICAgYXR0cmlidXRlczoge1xuICAgICAgICAgICAgY292ZXI6ICdodHRwczovL3N0b3JhZ2UuZ29vZ2xlYXBpcy5jb20vamFwYW5lc2UtZm9udC1mb3Itd29yZHByZXNzL2h1aWppLnBuZycsXG4gICAgICAgICAgICBhdXRob3I6ICdIdWkvcmFzcGkwMTI0JyxcbiAgICAgICAgICAgIHBhZ2VzOiA1MDBcbiAgICAgICAgfVxuICAgIH0sXG4gICAgYXR0cmlidXRlczoge1xuICAgICAgICB0ZXh0U3RyaW5nOiB7XG4gICAgICAgICAgICB0eXBlOiAnYXJyYXknLFxuICAgICAgICAgICAgc291cmNlOiAnY2hpbGRyZW4nLFxuICAgICAgICAgICAgc2VsZWN0b3I6ICdwJ1xuICAgICAgICB9XG4gICAgfSxcblxuICAgIC8vIHByb3BzIGFyZSBwYXNzZWQgdG8gZWRpdCBieSBkZWZhdWx0XG4gICAgLy8gcHJvcHMgY29udGFpbnMgdGhpbmdzIGxpa2Ugc2V0QXR0cmlidXRlcyBhbmQgYXR0cmlidXRlc1xuICAgIGVkaXQ6IGZ1bmN0aW9uIGVkaXQocHJvcHMpIHtcblxuICAgICAgICAvLyB3ZSBhcmUgcGVlbGluZyBvZmYgdGhlIHRoaW5ncyB3ZSBuZWVkXG4gICAgICAgIHZhciBzZXRBdHRyaWJ1dGVzID0gcHJvcHMuc2V0QXR0cmlidXRlcyxcbiAgICAgICAgICAgIGF0dHJpYnV0ZXMgPSBwcm9wcy5hdHRyaWJ1dGVzO1xuXG4gICAgICAgIC8vIFRoaXMgZnVuY3Rpb24gaXMgY2FsbGVkIHdoZW4gUmljaFRleHQgY2hhbmdlc1xuICAgICAgICAvLyBCeSBkZWZhdWx0IHRoZSBuZXcgc3RyaW5nIGlzIHBhc3NlZCB0byB0aGUgZnVuY3Rpb25cbiAgICAgICAgLy8gbm90IGFuIGV2ZW50IG9iamVjdCBsaWtlIHJlYWN0IG5vcm1hbGx5IHdvdWxkIGRvXG5cbiAgICAgICAgZnVuY3Rpb24gb25UZXh0Q2hhbmdlKGNoYW5nZXMpIHtcbiAgICAgICAgICAgIC8vIHdvcmtzIHZlcnkgbXVjaCBsaWtlIHNldFN0YXRlXG4gICAgICAgICAgICBzZXRBdHRyaWJ1dGVzKHtcbiAgICAgICAgICAgICAgICB0ZXh0U3RyaW5nOiBjaGFuZ2VzXG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoUmljaFRleHQsIHtcbiAgICAgICAgICAgIHRhZ05hbWU6ICdwJyxcbiAgICAgICAgICAgIHZhbHVlOiBhdHRyaWJ1dGVzLnRleHRTdHJpbmcsXG4gICAgICAgICAgICBvbkNoYW5nZTogb25UZXh0Q2hhbmdlLFxuICAgICAgICAgICAgY2xhc3NOYW1lOiAnd3AtYmxvY2stdGlueWpwZm9udC1ub3RvJyxcbiAgICAgICAgICAgIHBsYWNlaG9sZGVyOiAnRW50ZXIgeW91ciBmYXZvcml0ZSB0ZXh0IHdpdGggeW91ciBmZXZvcml0ZSBmb250ISB5YXkhJ1xuICAgICAgICB9KTtcbiAgICB9LFxuXG5cbiAgICAvLyBhZ2FpbiwgcHJvcHMgYXJlIGF1dG9tYXRpY2FsbHkgcGFzc2VkIHRvIHNhdmUgYW5kIGVkaXRcbiAgICBzYXZlOiBmdW5jdGlvbiBzYXZlKHByb3BzKSB7XG4gICAgICAgIHZhciBhdHRyaWJ1dGVzID0gcHJvcHMuYXR0cmlidXRlcztcblxuICAgICAgICAvLyBXZSB3YW50IHRoZSB0ZXh0IHRvIGJlIGFuIGgyIGVsZW1lbnRcbiAgICAgICAgLy8gYW5kIHdlIHBsYWNlIHRoZSB0ZXh0U3RyaW5nIHZhbHVlIGp1c3RcbiAgICAgICAgLy8gbGlrZSB3ZSB3b3VsZCBpbiBhIG5vcm1hbCByZWFjdCBhcHBcblxuICAgICAgICByZXR1cm4gd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuICAgICAgICAgICAgJ3AnLFxuICAgICAgICAgICAgeyAnY2xhc3MnOiAnd3AtYmxvY2stdGlueWpwZm9udC1ub3RvJyB9LFxuICAgICAgICAgICAgYXR0cmlidXRlcy50ZXh0U3RyaW5nXG4gICAgICAgICk7XG4gICAgfVxufSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvbm90by9ibG9jay5qc1xuLy8gbW9kdWxlIGlkID0gMVxuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwibWFwcGluZ3MiOiJBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///1\n");

/***/ }),
/* 2 */
/*!*****************************!*\
  !*** ./src/noto/style.scss ***!
  \*****************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMi5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ub3RvL3N0eWxlLnNjc3M/MmI5ZCJdLCJzb3VyY2VzQ29udGVudCI6WyIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL25vdG8vc3R5bGUuc2Nzc1xuLy8gbW9kdWxlIGlkID0gMlxuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwibWFwcGluZ3MiOiJBQUFBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///2\n");

/***/ }),
/* 3 */
/*!******************************!*\
  !*** ./src/noto/editor.scss ***!
  \******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMy5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ub3RvL2VkaXRvci5zY3NzP2MwMTQiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9ub3RvL2VkaXRvci5zY3NzXG4vLyBtb2R1bGUgaWQgPSAzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///3\n");

/***/ }),
/* 4 */
/*!****************************!*\
  !*** ./src/huiji/block.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss__ = __webpack_require__(/*! ./style.scss */ 5);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 6);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__editor_scss__);\n//  Import CSS.\n\n\n\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\nvar RichText = wp.blockEditor.RichText;\n\n\nregisterBlockType('tinyjpfont/huiji', {\n    title: \"ふい字\",\n    icon: \"edit\",\n    category: 'common',\n    example: {\n        attributes: {\n            cover: 'https://storage.googleapis.com/japanese-font-for-wordpress/huiji.png',\n            author: 'Hui/raspi0124',\n            pages: 500\n        }\n    },\n    attributes: {\n        textString: {\n            type: 'array',\n            source: 'children',\n            selector: 'p'\n        }\n    },\n\n    // props are passed to edit by default\n    // props contains things like setAttributes and attributes\n    edit: function edit(props) {\n\n        // we are peeling off the things we need\n        var setAttributes = props.setAttributes,\n            attributes = props.attributes;\n\n        // This function is called when RichText changes\n        // By default the new string is passed to the function\n        // not an event object like react normally would do\n\n        function onTextChange(changes) {\n            // works very much like setState\n            setAttributes({\n                textString: changes\n            });\n        }\n\n        return wp.element.createElement(RichText, {\n            tagName: 'p',\n            value: attributes.textString,\n            onChange: onTextChange,\n            className: 'wp-block-tinyjpfont-huiji',\n            placeholder: 'Enter your favorite text with your fevorite font! yay!'\n        });\n    },\n\n\n    // again, props are automatically passed to save and edit\n    save: function save(props) {\n        var attributes = props.attributes;\n\n        // We want the text to be an h2 element\n        // and we place the textString value just\n        // like we would in a normal react app\n\n        return wp.element.createElement(\n            'p',\n            { 'class': 'wp-block-tinyjpfont-huiji' },\n            attributes.textString\n        );\n    }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiNC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9odWlqaS9ibG9jay5qcz8yMjYxIl0sInNvdXJjZXNDb250ZW50IjpbIi8vICBJbXBvcnQgQ1NTLlxuaW1wb3J0ICcuL3N0eWxlLnNjc3MnO1xuaW1wb3J0ICcuL2VkaXRvci5zY3NzJztcblxudmFyIF9fID0gd3AuaTE4bi5fXztcbnZhciByZWdpc3RlckJsb2NrVHlwZSA9IHdwLmJsb2Nrcy5yZWdpc3RlckJsb2NrVHlwZTtcbnZhciBSaWNoVGV4dCA9IHdwLmJsb2NrRWRpdG9yLlJpY2hUZXh0O1xuXG5cbnJlZ2lzdGVyQmxvY2tUeXBlKCd0aW55anBmb250L2h1aWppJywge1xuICAgIHRpdGxlOiBcIuOBteOBhOWtl1wiLFxuICAgIGljb246IFwiZWRpdFwiLFxuICAgIGNhdGVnb3J5OiAnY29tbW9uJyxcbiAgICBleGFtcGxlOiB7XG4gICAgICAgIGF0dHJpYnV0ZXM6IHtcbiAgICAgICAgICAgIGNvdmVyOiAnaHR0cHM6Ly9zdG9yYWdlLmdvb2dsZWFwaXMuY29tL2phcGFuZXNlLWZvbnQtZm9yLXdvcmRwcmVzcy9odWlqaS5wbmcnLFxuICAgICAgICAgICAgYXV0aG9yOiAnSHVpL3Jhc3BpMDEyNCcsXG4gICAgICAgICAgICBwYWdlczogNTAwXG4gICAgICAgIH1cbiAgICB9LFxuICAgIGF0dHJpYnV0ZXM6IHtcbiAgICAgICAgdGV4dFN0cmluZzoge1xuICAgICAgICAgICAgdHlwZTogJ2FycmF5JyxcbiAgICAgICAgICAgIHNvdXJjZTogJ2NoaWxkcmVuJyxcbiAgICAgICAgICAgIHNlbGVjdG9yOiAncCdcbiAgICAgICAgfVxuICAgIH0sXG5cbiAgICAvLyBwcm9wcyBhcmUgcGFzc2VkIHRvIGVkaXQgYnkgZGVmYXVsdFxuICAgIC8vIHByb3BzIGNvbnRhaW5zIHRoaW5ncyBsaWtlIHNldEF0dHJpYnV0ZXMgYW5kIGF0dHJpYnV0ZXNcbiAgICBlZGl0OiBmdW5jdGlvbiBlZGl0KHByb3BzKSB7XG5cbiAgICAgICAgLy8gd2UgYXJlIHBlZWxpbmcgb2ZmIHRoZSB0aGluZ3Mgd2UgbmVlZFxuICAgICAgICB2YXIgc2V0QXR0cmlidXRlcyA9IHByb3BzLnNldEF0dHJpYnV0ZXMsXG4gICAgICAgICAgICBhdHRyaWJ1dGVzID0gcHJvcHMuYXR0cmlidXRlcztcblxuICAgICAgICAvLyBUaGlzIGZ1bmN0aW9uIGlzIGNhbGxlZCB3aGVuIFJpY2hUZXh0IGNoYW5nZXNcbiAgICAgICAgLy8gQnkgZGVmYXVsdCB0aGUgbmV3IHN0cmluZyBpcyBwYXNzZWQgdG8gdGhlIGZ1bmN0aW9uXG4gICAgICAgIC8vIG5vdCBhbiBldmVudCBvYmplY3QgbGlrZSByZWFjdCBub3JtYWxseSB3b3VsZCBkb1xuXG4gICAgICAgIGZ1bmN0aW9uIG9uVGV4dENoYW5nZShjaGFuZ2VzKSB7XG4gICAgICAgICAgICAvLyB3b3JrcyB2ZXJ5IG11Y2ggbGlrZSBzZXRTdGF0ZVxuICAgICAgICAgICAgc2V0QXR0cmlidXRlcyh7XG4gICAgICAgICAgICAgICAgdGV4dFN0cmluZzogY2hhbmdlc1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFJpY2hUZXh0LCB7XG4gICAgICAgICAgICB0YWdOYW1lOiAncCcsXG4gICAgICAgICAgICB2YWx1ZTogYXR0cmlidXRlcy50ZXh0U3RyaW5nLFxuICAgICAgICAgICAgb25DaGFuZ2U6IG9uVGV4dENoYW5nZSxcbiAgICAgICAgICAgIGNsYXNzTmFtZTogJ3dwLWJsb2NrLXRpbnlqcGZvbnQtaHVpamknLFxuICAgICAgICAgICAgcGxhY2Vob2xkZXI6ICdFbnRlciB5b3VyIGZhdm9yaXRlIHRleHQgd2l0aCB5b3VyIGZldm9yaXRlIGZvbnQhIHlheSEnXG4gICAgICAgIH0pO1xuICAgIH0sXG5cblxuICAgIC8vIGFnYWluLCBwcm9wcyBhcmUgYXV0b21hdGljYWxseSBwYXNzZWQgdG8gc2F2ZSBhbmQgZWRpdFxuICAgIHNhdmU6IGZ1bmN0aW9uIHNhdmUocHJvcHMpIHtcbiAgICAgICAgdmFyIGF0dHJpYnV0ZXMgPSBwcm9wcy5hdHRyaWJ1dGVzO1xuXG4gICAgICAgIC8vIFdlIHdhbnQgdGhlIHRleHQgdG8gYmUgYW4gaDIgZWxlbWVudFxuICAgICAgICAvLyBhbmQgd2UgcGxhY2UgdGhlIHRleHRTdHJpbmcgdmFsdWUganVzdFxuICAgICAgICAvLyBsaWtlIHdlIHdvdWxkIGluIGEgbm9ybWFsIHJlYWN0IGFwcFxuXG4gICAgICAgIHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG4gICAgICAgICAgICAncCcsXG4gICAgICAgICAgICB7ICdjbGFzcyc6ICd3cC1ibG9jay10aW55anBmb250LWh1aWppJyB9LFxuICAgICAgICAgICAgYXR0cmlidXRlcy50ZXh0U3RyaW5nXG4gICAgICAgICk7XG4gICAgfVxufSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvaHVpamkvYmxvY2suanNcbi8vIG1vZHVsZSBpZCA9IDRcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///4\n");

/***/ }),
/* 5 */
/*!******************************!*\
  !*** ./src/huiji/style.scss ***!
  \******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiNS5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9odWlqaS9zdHlsZS5zY3NzPzk2NTkiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9odWlqaS9zdHlsZS5zY3NzXG4vLyBtb2R1bGUgaWQgPSA1XG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///5\n");

/***/ }),
/* 6 */
/*!*******************************!*\
  !*** ./src/huiji/editor.scss ***!
  \*******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiNi5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9odWlqaS9lZGl0b3Iuc2Nzcz9jMjg1Il0sInNvdXJjZXNDb250ZW50IjpbIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvaHVpamkvZWRpdG9yLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDZcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///6\n");

/***/ })
/******/ ]);