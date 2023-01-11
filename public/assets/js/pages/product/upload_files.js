var uppy = new Uppy.Uppy()
    .use(Uppy.Dashboard, {
        inline: true,
        target: '#drag-drop-area',
        locale: {
            strings: {
                // When `inline: false`, used as the screen reader label for the button that closes the modal.
                closeModal: 'Close Modal',
                // Used as the screen reader label for the plus (+) button that shows the “Add more files” screen
                addMoreFiles: 'Add more files',
                addingMoreFiles: 'Adding more files',
                // Used as the header for import panels, e.g., “Import from Google Drive”.
                importFrom: 'Import from %{name}',
                // When `inline: false`, used as the screen reader label for the dashboard modal.
                dashboardWindowTitle: 'Uppy Dashboard Window (Press escape to close)',
                // When `inline: true`, used as the screen reader label for the dashboard area.
                dashboardTitle: 'Uppy Dashboard',
                // Shown in the Informer when a link to a file was copied to the clipboard.
                copyLinkToClipboardSuccess: 'Link copied to clipboard.',
                // Used when a link cannot be copied automatically — the user has to select the text from the
                // input element below this string.
                copyLinkToClipboardFallback: 'Copy the URL below',
                // Used as the hover title and screen reader label for buttons that copy a file link.
                copyLink: 'Copy link',
                back: 'Back',
                // Used as the screen reader label for buttons that remove a file.
                removeFile: 'Remove file',
                // Used as the screen reader label for buttons that open the metadata editor panel for a file.
                editFile: 'Edit file',
                // Shown in the panel header for the metadata editor. Rendered as “Editing image.png”.
                editing: 'Editing %{file}',
                // Used as the screen reader label for the button that saves metadata edits and returns to the
                // file list view.
                finishEditingFile: 'Finish editing file',
                saveChanges: 'Save changes',
                // Used as the label for the tab button that opens the system file selection dialog.
                myDevice: 'Mi pc',
                dropHint: 'Arrastre sus archivos aquí',
                // Used as the hover text and screen reader label for file progress indicators when
                // they have been fully uploaded.
                uploadComplete: 'Subida completada',
                uploadPaused: 'Subida pausada',
                // Used as the hover text and screen reader label for the buttons to resume paused uploads.
                resumeUpload: 'Resume upload',
                // Used as the hover text and screen reader label for the buttons to pause uploads.
                pauseUpload: 'Pause upload',
                // Used as the hover text and screen reader label for the buttons to retry failed uploads.
                retryUpload: 'Retry upload',
                // Used as the hover text and screen reader label for the buttons to cancel uploads.
                cancelUpload: 'Cancel upload',
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
                editFileWithFilename: 'Edit file %{file}',
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
    .use(Uppy.Tus, {
        endpoint: 'https://tusd.tusdemo.net/files/'
    })

uppy.on('complete', (result) => {
    console.log('Upload complete! We’ve uploaded these files:', result.successful)
})