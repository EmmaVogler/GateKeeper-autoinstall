import os

directorio = 'D:/Catalogo WEEZ/Archivos a instalar'

# Recorre todas las carpetas y subcarpetas
for carpeta_raiz, subcarpetas, archivos in os.walk(directorio):
    print(f'Carpeta: {carpeta_raiz}')
    for subcarpeta in subcarpetas:
        print(f'  Subcarpeta: {subcarpeta}')
    for archivo in archivos:
        print(f'  Archivo: {archivo}')
