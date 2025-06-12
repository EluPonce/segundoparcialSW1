editor.Panels.addButton('options', [{
  id: 'export-dart',
  className: 'btn-export-dart',
  label: 'Exportar a Flutter',
  command: 'export-dart',
}]);

editor.Commands.add('export-dart', {
  run(editor, sender) {
    sender.set('active', false); // Desactivar botón para evitar múltiples clics

    // Obtener HTML y CSS generados
    const rawHtml = editor.getHtml();
    const rawCss = editor.getCss();

    // Quitar etiquetas <script> del HTML (causan errores en Flutter)
    const cleanHtml = rawHtml.replace(/<script[^>]*>[\s\S]*?<\/script>/gi, '');

    // Escapar caracteres problemáticos para Dart (`, $, \)
    const escapeForDart = str =>
      str.replace(/\\/g, '\\\\').replace(/`/g, '\\`').replace(/\$/g, '\\$');

    const htmlEscaped = escapeForDart(cleanHtml);
    const cssEscaped = escapeForDart(rawCss);

    // Código Dart generado
    const dartCode = `
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

class ComponenteGenerado extends StatelessWidget {
  const ComponenteGenerado({super.key});

  @override
  Widget build(BuildContext context) {
    final String contentBase64 = base64Encode(const Utf8Encoder().convert(\`
<!DOCTYPE html>
<html>
  <head>
    <style>
      ${cssEscaped}
    </style>
  </head>
  <body>
    ${htmlEscaped}
  </body>
</html>
\`));

    return Scaffold(
      appBar: AppBar(title: const Text('Vista generada')),
      body: const WebViewWidget(),
    );
  }
}

class WebViewWidget extends StatefulWidget {
  const WebViewWidget({super.key});

  @override
  State<WebViewWidget> createState() => _WebViewWidgetState();
}

class _WebViewWidgetState extends State<WebViewWidget> {
  late final WebViewController _controller;

  @override
  void initState() {
    super.initState();
    final String html = utf8.decode(base64.decode(contentBase64));
    _controller = WebViewController()
      ..loadHtmlString(html)
      ..setJavaScriptMode(JavaScriptMode.unrestricted);
  }

  @override
  Widget build(BuildContext context) {
    return WebViewWidget(controller: _controller);
  }
}
`;

    // Crear y descargar el archivo Dart
    const blob = new Blob([dartCode], { type: 'text/plain' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'componente_generado.dart';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }
});
