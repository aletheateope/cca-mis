FilePond.registerPlugin(FilePondPluginFileRename);
FilePond.registerPlugin(FilePondPluginFileValidateType);

const inputElement = document.querySelector("#inputStudentDocuments");

FilePond.create(inputElement, {
  //   allowFileRename: true,
  acceptedFileTypes: ["application/pdf"],
  allowMultiple: true,
  allowReorder: true,
});

// FilePond.setOptions({
//   fileRenameFunction: (file) =>
//     new Promise((resolve) => {
//       resolve(
//         window.prompt(
//           "For more organized files, please rename the file",
//           file.name
//         )
//       );
//     }),
// });
