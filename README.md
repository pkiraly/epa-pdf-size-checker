# EPA pdf page number checker

Checker tool for Elektronikus Periodika Adatbázis [EPA](http://epa.oszk.hu/) for compare the PDF files and the table of content entries if they are coherent

## Preparation:

1. Download "Xpdf tools" from http://www.xpdfreader.com/download.html
2. Extract the files, and add the directory to your PATH system variable where the `pdfinfo` exists in your system (for 64bit Linux it locates in the directory 'xpdf/xpdf-tools-linux-4.00/bin64/')

## Run:

```
php xml2toc.php --directory [directory]
```

The directory could be any directory. In these directories there is an index.xml file which contains the table of contents, and the PDF files. The tool extracts the file name and the page ranges from the XML, calls pdfinfo to extract the page numbers, and compares them. If there is a difference is tells you the error, such as 

```
03000/03048/00008/pdf/EPA03048_leveltari_2017_4_085-087.pdf: Different page numbers in XML and in PDF. XML: 3 (85-87), PDF: 2
```

which tells you that according to the XML the PDF should have 3 pages but it has only 2. Both the XML and the PDF could be false, so it worth to check both.