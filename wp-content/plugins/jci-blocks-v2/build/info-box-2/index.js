/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./src/info-box-2/block.json":
/*!***********************************!*\
  !*** ./src/info-box-2/block.json ***!
  \***********************************/
/***/ ((module) => {

module.exports = JSON.parse('{"apiVersion":2,"name":"jci-blocks/info-box-2","version":"0.1.1","title":"Info Box (depracated)","category":"jci-category","icon":"admin-links","supports":{"html":false},"attributes":{"text":{"type":"string","source":"html","selector":"p"},"name":{"type":"string","source":"html","selector":".info-box-name"},"position":{"type":"string","source":"html","selector":".info-box-position"},"icon":{"type":"string","default":"quotes","source":"attribute","attribute":"value","selector":"select"},"buttonText":{"type":"string","source":"text","selector":"a"},"buttonLink":{"type":"string","source":"attribute","attribute":"href","selector":"a"}},"textdomain":"jci-blocks","editorScript":"file:./index.js"}');

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*********************************!*\
  !*** ./src/info-box-2/index.js ***!
  \*********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./block.json */ "./src/info-box-2/block.json");







(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_6__.name, {
  deprecated: [{
    save: ({
      attributes
    }) => {
      const blockProps = _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps.save();
      const {
        text,
        name,
        position,
        icon,
        buttonText,
        buttonLink
      } = attributes;
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: `wp-block-jci-blocks-info-box ${icon}`
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText.Content, {
        ...blockProps,
        tagName: "p",
        className: "info-box-quote",
        value: text
      }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText.Content, {
        ...blockProps,
        tagName: "p",
        className: "info-box-name",
        value: name
      }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText.Content, {
        ...blockProps,
        tagName: "p",
        className: "info-box-position",
        value: position
      }), buttonText && buttonLink && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Button, {
        href: buttonLink,
        target: "_blank",
        text: buttonText,
        className: "info-box-button"
      }));
    }
  }],
  edit: ({
    attributes,
    setAttributes
  }) => {
    console.log('attributes edit ', attributes);
    const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps)();
    const {
      icon,
      text,
      name,
      position,
      buttonText,
      buttonLink
    } = attributes;
    // const [icon] = attributes;
    // console.log( 'icon is ',icon);
    // const [ myIcon, setIcon ] = useState('quotes'); 
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.SelectControl, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Select Icon', 'jci-blocks'),
      value: icon,
      className: "select-test",
      onChange: value => setAttributes({
        icon: value
      })
      // onChange={ ( newIcon ) => setIcon( newIcon )}
      ,
      options: [{
        label: 'quotes',
        value: 'quotes'
      }, {
        label: 'active',
        value: 'active'
      }, {
        label: 'adventure',
        value: 'adventure'
      }, {
        label: 'city',
        value: 'city'
      }, {
        label: 'dollar',
        value: 'dollar'
      }, {
        label: 'education',
        value: 'education'
      }, {
        label: 'food',
        value: 'food'
      }, {
        label: 'fun fact',
        value: 'fun-fact'
      }, {
        label: 'health',
        value: 'health'
      }, {
        label: 'link',
        value: 'link'
      }, {
        label: 'logo',
        value: 'logo'
      }, {
        label: 'love',
        value: 'love'
      }, {
        label: 'metro',
        value: 'metro'
      }, {
        label: 'music',
        value: 'music'
      }, {
        label: 'neighborhood',
        value: 'neighborhood'
      }, {
        label: 'nightlife',
        value: 'nightlife'
      }, {
        label: 'pets',
        value: 'pets'
      }, {
        label: 'question mark',
        value: 'question-mark'
      }, {
        label: 'sports',
        value: 'sports'
      }, {
        label: 'tourism',
        value: 'tourism'
      }, {
        label: 'transportation',
        value: 'transportation'
      }]
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText, {
      ...blockProps,
      tagName: "p",
      className: "info-box-quote",
      value: text,
      onChange: text => setAttributes(text),
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Quote...', 'jci_blocks')
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText, {
      ...blockProps,
      tagName: "p",
      className: "info-box-name",
      value: name,
      onChange: name => setAttributes({
        name
      }),
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Name...', 'jci_blocks')
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText, {
      ...blockProps,
      tagName: "p",
      className: "info-box-position",
      value: position,
      onChange: position => setAttributes({
        position
      }),
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Position...', 'jci_blocks')
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.TextControl, {
      label: "Button Text (optional)",
      value: buttonText,
      onChange: buttonText => setAttributes({
        buttonText
      })
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.TextControl, {
      label: "Button Link (optional)",
      value: buttonLink,
      onChange: buttonLink => setAttributes({
        buttonLink
      })
    }));
  },
  save: ({
    attributes
  }) => {
    const blockProps = _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps.save();
    const {
      text,
      name,
      position,
      icon,
      buttonText,
      buttonLink
    } = attributes;
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: `wp-block-jci-blocks-info-box ${icon}`
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText.Content, {
      ...blockProps,
      tagName: "p",
      className: "info-box-quote",
      value: text
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText.Content, {
      ...blockProps,
      tagName: "p",
      className: "info-box-name",
      value: name
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.RichText.Content, {
      ...blockProps,
      tagName: "p",
      className: "info-box-position",
      value: position
    }), buttonText && buttonLink && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Button, {
      href: buttonLink,
      target: "_blank",
      text: buttonText,
      className: "info-box-button"
    }));
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map