// Paneles básicos (como los botones de exportar)
editor.Panels.addButton('options', [{
    id: 'export',
    className: 'btn-open-export',
    label: 'Exportar a Angular',
    command: 'export-template-angular',  // Aquí se activará el comando export-template-angular
    context: 'export-template-angular',
  }]);
  // export-component.js


  editor.Commands.add('export-template', {
    run(editor, sender) {
        sender.set('active', false);  // Desactivar el botón para no hacer múltiples clics

        // Obtener el HTML y CSS del gráfico
        const html = editor.getHtml();
        const css = editor.getCss();

        // Nombre del componente para la carpeta ZIP (podrías obtener esto dinámicamente si es necesario)
        const componentName = 'custom-component'; // Nombre del componente Angular, puedes obtenerlo dinámicamente.

        // Crear el archivo HTML con el contenido del gráfico
        const htmlBlob = new Blob([html], { type: 'text/html' });
        const scssBlob = new Blob([css], { type: 'text/css' });

        // Crear el archivo TypeScript básico para el componente Angular
        const tsContent = `
import { Component } from '@angular/core';

@Component({
  selector: 'app-${componentName}',
  templateUrl: './component.html',
  styleUrls: ['./component.scss']
})
export class CustomComponent {
  // Aquí puedes agregar la lógica del componente si es necesario
}`;
        const tsBlob = new Blob([tsContent], { type: 'text/javascript' });

        // Crear el archivo ZIP
        const zip = new JSZip();

        // Agregar los archivos al ZIP
        zip.folder(componentName)  // Crear una carpeta con el nombre del componente
            .file('component.html', htmlBlob)
            .file('component.scss', scssBlob)
            .file('component.ts', tsBlob);

        // Generar el archivo ZIP y descargar lo
        zip.generateAsync({ type: 'blob' }).then(function(content) {
            // Crear un enlace de descarga y hacer clic en él
            const zipLink = document.createElement('a');
            zipLink.href = URL.createObjectURL(content);
            zipLink.download = `${componentName}.zip`;  // Nombre del archivo ZIP
            zipLink.click();
        });
    },
});
