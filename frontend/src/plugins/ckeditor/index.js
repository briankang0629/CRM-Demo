// Basic
import Vue from 'vue'
import ckeditor from '@ckeditor/ckeditor5-vue2'

Vue.use(ckeditor)

// Plugin
import classicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor'

export const editor = classicEditor

import essentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials'
import boldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold'
import italicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic'
import underlinePlugin from '@ckeditor/ckeditor5-basic-styles/src/underline'
import codePlugin from '@ckeditor/ckeditor5-basic-styles/src/code'
import superscriptPlugin from '@ckeditor/ckeditor5-basic-styles/src/superscript'
import subscriptPlugin from '@ckeditor/ckeditor5-basic-styles/src/subscript'
import strikeThroughPlugin from '@ckeditor/ckeditor5-basic-styles/src/strikethrough'
import linkPlugin from '@ckeditor/ckeditor5-link/src/link'
import fontPlugin from '@ckeditor/ckeditor5-font/src/font'
import headingPlugin from '@ckeditor/ckeditor5-heading/src/heading'
import paragraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph'
import alignment from '@ckeditor/ckeditor5-alignment/src/alignment'
import htmlEmbed from '@ckeditor/ckeditor5-html-embed/src/htmlembed'
import indent from '@ckeditor/ckeditor5-indent/src/indent'
import indentBlock from '@ckeditor/ckeditor5-indent/src/indentblock'
import blockQuote from "@ckeditor/ckeditor5-block-quote/src/blockquote"
import codeBlock from "@ckeditor/ckeditor5-code-block/src/codeblock"
import table from '@ckeditor/ckeditor5-table/src/table'
import tableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar'
import tableProperties from '@ckeditor/ckeditor5-table/src/tableproperties'
import tableCellProperties from '@ckeditor/ckeditor5-table/src/tablecellproperties'
import removeFormat from '@ckeditor/ckeditor5-remove-format/src/removeformat'
import mediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed'
import listStyle from '@ckeditor/ckeditor5-list/src/liststyle'
import image from '@ckeditor/ckeditor5-image/src/image'
import imageInsert from '@ckeditor/ckeditor5-image/src/imageinsert'
import imageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar'
import imageCaption from '@ckeditor/ckeditor5-image/src/imagecaption'
import imageStyle from '@ckeditor/ckeditor5-image/src/imagestyle'
import imageResize from '@ckeditor/ckeditor5-image/src/imageresize'
import linkImage from '@ckeditor/ckeditor5-link/src/linkimage'
import simpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter'

export const plugins = [
    essentialsPlugin,
    boldPlugin,
    italicPlugin,
    underlinePlugin,
    superscriptPlugin,
    subscriptPlugin,
    strikeThroughPlugin,
    headingPlugin,
    fontPlugin,
    codePlugin,
    linkPlugin, paragraphPlugin,
    alignment,
    htmlEmbed,
    indent,
    indentBlock,
    blockQuote, codeBlock,
    table,
    tableToolbar,
    tableProperties,
    tableCellProperties,
    removeFormat,
    mediaEmbed,
    listStyle,
    image,
    imageToolbar,
    imageCaption,
    imageStyle,
    imageResize,
    linkImage,
    imageInsert,
    simpleUploadAdapter
]

// Toolbar
export const toolbar = {
    items: [
        'heading',
        'undo', 'redo',
        'bold', 'italic', 'underline', 'strikethrough', 'code', 'superscript', 'subscript',
        'alignment',
        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
        'outdent', 'indent',
        'bulletedList', 'numberedList',
        'blockQuote',
        'removeFormat',
        'link',
        'ImageInsert',
        'mediaEmbed',
        'insertTable',
        'codeBlock',
        'htmlEmbed'
    ],
    shouldNotGroupWhenFull: true
}

// Table
export const tableConfig = {
    contentToolbar: [
        'tableColumn', 'tableRow', 'mergeTableCells',
        'tableProperties', 'tableCellProperties'
    ],

    // Configuration of the TableProperties plugin.
    tableProperties: {
        // ...
    },

    // Configuration of the TableCellProperties plugin.
    tableCellProperties: {
        // ...
    }
}

//Image
import resizeOptions from '@/plugins/ckeditor/resizeOptions'

export const imageConfig = {
    toolbar: [
        'linkImage',
        'imageTextAlternative',
        'imageResize',
        'imageStyle:full',
        'imageStyle:side',
        'imageStyle:alignLeft',
        'imageStyle:alignCenter',
        'imageStyle:alignRight'
    ],
    styles: [
        'alignLeft', 'alignCenter', 'alignRight', 'full', 'side'
    ],
    resizeOptions
}

// Font Color
import fontColor from '@/plugins/ckeditor/color'
export const color = fontColor

// Export default main setting
export default {
    editor: editor,
    config: {
        plugins: plugins,
        toolbar: toolbar,
        heading: {
            options: [
                {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
                {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'},
                {model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'},
                {model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'},
                {model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6'}
            ]
        },
        table: tableConfig,
        image: imageConfig,
        fontColor: color,
        fontBackgroundColor: color,
        mediaEmbed: {
            previewsInData: true
        },
        simpleUpload: {
            uploadUrl: '/api/media/image/store',
            withCredentials: true,
            headers: {
                'Authorization': JSON.parse(localStorage.getItem('auth')).token
            }
        }
    }
}
