const input = document.getElementById('product_images');
let images = [];

var uppy = new Uppy.Uppy()
    .use(Uppy.Dashboard, {
        inline: true,
        target: '#drag-drop-area',
        showUploadButton: false,
        showLinkToFileUploadResult: false,
        showProgressDetails: false,
        width: "100%",
        locale: {
            strings: {
                // When `inline: false`, used as the screen reader label for the button that closes the modal.
                closeModal: 'Cerrar Modal',
                // Used as the screen reader label for the plus (+) button that shows the “Add more files” screen
                addMoreFiles: 'Agregar mas archivos',
                addingMoreFiles: 'Agregando mas archivos',
                // Used as the header for import panels, e.g., “Import from Google Drive”.
                importFrom: 'Importar desde %{name}',
                // When `inline: false`, used as the screen reader label for the dashboard modal.
                dashboardWindowTitle: 'Uppy Dashboard Window (Preciones Esc para cerrar)',
                // When `inline: true`, used as the screen reader label for the dashboard area.
                dashboardTitle: 'Uppy Dashboard',
                // Shown in the Informer when a link to a file was copied to the clipboard.
                copyLinkToClipboardSuccess: 'Link copied to clipboard.',
                // Used when a link cannot be copied automatically — the user has to select the text from the
                // input element below this string.
                copyLinkToClipboardFallback: 'Copiar la URL debajo',
                // Used as the hover title and screen reader label for buttons that copy a file link.
                copyLink: 'Copiar link',

                back: 'Volver',
                // Used as the screen reader label for buttons that remove a file.
                removeFile: 'Eliminar archivo',
                // Used as the screen reader label for buttons that open the metadata editor panel for a file.
                editFile: 'Editar archivo',
                // Shown in the panel header for the metadata editor. Rendered as “Editing image.png”.
                editing: 'Editando %{file}',
                // Used as the screen reader label for the button that saves metadata edits and returns to the
                // file list view.
                finishEditingFile: 'Finalizando edicion de archivo',
                saveChanges: 'Guardar cambios',
                // Used as the label for the tab button that opens the system file selection dialog.
                myDevice: 'Mi pc',
                dropHint: 'Arrastre sus archivos aquí',
                // Used as the hover text and screen reader label for file progress indicators when
                // they have been fully uploaded.
                uploadComplete: 'Subida completada',
                uploadPaused: 'Subida pausada',
                // Used as the hover text and screen reader label for the buttons to resume paused uploads.
                resumeUpload: 'Resumen de subida',
                // Used as the hover text and screen reader label for the buttons to pause uploads.
                pauseUpload: 'Pausar subida',
                // Used as the hover text and screen reader label for the buttons to retry failed uploads.
                retryUpload: 'Reintentar subida',
                // Used as the hover text and screen reader label for the buttons to cancel uploads.
                cancelUpload: 'Cancelar subida',
                // Used in a title, how many files are currently selected
                xFilesSelected: {
                    0: '%{smart_count} archivo seleccionado',
                    1: '%{smart_count} archivos seleccionados',
                },
                uploadingXFiles: {
                    0: 'Subiendo %{smart_count} archivo',
                    1: 'Subiendo %{smart_count} archivos',
                },
                processingXFiles: {
                    0: 'Procesando %{smart_count} archivo',
                    1: 'Procesando %{smart_count} archivos',
                },
                // The "powered by Uppy" link at the bottom of the Dashboard.
                poweredBy: '',
                addMore: 'Añadir mas',
                editFileWithFilename: 'Editar archivo %{file}',
                save: 'Guardar',
                cancel: 'Cancelar',
                dropPasteFiles: 'Arraste las imagenes hasta aquí ó %{browseFiles}',
                dropPasteFolders: 'Arraste las imagenes hasta aquí ó %{browseFolders}',
                dropPasteBoth: 'Drop files here, %{browseFiles} or %{browseFolders}',
                dropPasteImportFiles: 'Drop files here, %{browseFiles} or import from:',
                dropPasteImportFolders: 'Drop files here, %{browseFolders} or import from:',
                dropPasteImportBoth:
                    'Drop files here, %{browseFiles}, %{browseFolders} or import from:',
                importFiles: 'Import files from:',
                browseFiles: 'selecciones las imagenes desde la pc.',
                browseFolders: 'browse folders',
                recoveredXFiles: {
                    0: 'We could not fully recover 1 file. Please re-select it and resume the upload.',
                    1: 'We could not fully recover %{smart_count} files. Please re-select them and resume the upload.',
                },
                recoveredAllFiles: 'We restored all files. You can now resume the upload.',
                sessionRestored: 'Session restored',
                reSelect: 'Re-select',
                missingRequiredMetaFields: {
                    0: 'Missing required meta field: %{fields}.',
                    1: 'Missing required meta fields: %{fields}.',
                },
                // Used for native device camera buttons on mobile
                takePictureBtn: 'Take Picture',
                recordVideoBtn: 'Record Video',
            },
        }
    })
uppy.on('file-added', (file) => {
    const reader = new FileReader();
    reader.onload = (event) => {
        const base64 = event.target.result;
        images.push(base64);
        input.value = images.join('*,*');
    }
    reader.readAsDataURL(file.data);
});

uppy.on('file-removed', (file) => {
    const reader = new FileReader();
    reader.onload = (event) => {
        const base64 = event.target.result;
        const index = images.indexOf(base64);
        if (index > -1) {
            images.splice(index, 1);
            input.value = images;
        }
        input.value = images.join(',');
    }
    reader.readAsDataURL(file.data);
});

