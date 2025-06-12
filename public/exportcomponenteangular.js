document.addEventListener('DOMContentLoaded', () => {
    const exportButton = document.querySelector('.export-button');

    exportButton.addEventListener('click', () => {
        const components = editor.getWrapper().components();
        const flutterCode = generateFlutterCode(components);

        const fileName = "pantalla_generada.dart";
        const blob = new Blob([flutterCode], { type: "text/plain;charset=utf-8" });

        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = fileName;
        link.click();
    });

    function generateFlutterCode(components) {
        const codeLines = [
            "import 'package:flutter/material.dart';",
            "",
            "void main() => runApp(PantallaGenerada());",
            "",
            "class PantallaGenerada extends StatelessWidget {",
            "  @override",
            "  Widget build(BuildContext context) {",
            "    return MaterialApp(",
            "      home: Scaffold(",
            "        appBar: AppBar(title: Text('Interfaz Generada')),",
            "        body: Padding(",
            "          padding: EdgeInsets.all(16.0),",
            "          child: Column(",
            "            crossAxisAlignment: CrossAxisAlignment.start,",
            "            children: ["
        ];

        components.each(component => {
            const tag = component.get('tagName');
            const attrs = component.getAttributes();
            const content = component.view?.el?.innerText?.trim() || '';

            if (tag === 'p' || tag === 'label' || tag === 'div') {
                codeLines.push(`              Text('${content}'),`);
            } else if (tag === 'button') {
                codeLines.push(`              ElevatedButton(onPressed: () {}, child: Text('${content}')),`);
            } else if (tag === 'img') {
                const src = attrs.src || 'https://via.placeholder.com/150';
                codeLines.push(`              Image.network('${src}'),`);
            } else if (tag === 'input') {
                codeLines.push(`              TextField(decoration: InputDecoration(hintText: '${attrs.placeholder || ''}')),`);
            } else {
                codeLines.push("              // Componente no reconocido");
            }
        });

        codeLines.push(
            "            ],",
            "          ),",
            "        ),",
            "      ),",
            "    );",
            "  }",
            "}"
        );

        return codeLines.join("\n");
    }
});
