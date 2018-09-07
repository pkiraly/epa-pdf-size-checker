<?php

define('EPA_PREFIX', 'http://epa.oszk.hu/');
define('PDFINFO_PATTERN', "pdfinfo %s | grep 'Pages:' | awk '{print \$2}'");

$shortopts = "d:";
$longopts  = ["directory:"];

$options = getopt($shortopts, $longopts);

if (!isset($options['d']) && !isset($options["directory"])) {
  die("Missing mandatory argument: directory\n");
}

if (isset($options['d'])) {
  $directory = $options['d'];
} else if (isset($options['directory'])) {
  $directory = $options['directory'];
}

if (!file_exists($directory)) {
  die(sprintf("The directory '%s' is not existing.", $directory));
}

$index_file = sprintf("%s/index.xml", $directory);
if (!file_exists($index_file)) {
  die(sprintf("The index file '%s' is not existing.", $index_file));
}

$prefix = 'http://epa.oszk.hu/';
$lines = file($index_file);

foreach ($lines as $line_num => $line) {
  if (preg_match('/<Article>/', $line, $matches)) {
    $record = [];
  }
  if (preg_match('/<\/Article>/', $line, $matches)) {
    $cmd = sprintf(PDFINFO_PATTERN, $record['pdf']);
    $page_count = exec($cmd);
    $record['page_count'] = $page_count;
    if ($record['page_count'] != $record['expected']) {
      printf("%s: Different page numbers in XML and in PDF. XML: %d (%s), PDF: %d\n", $record['pdf'], $record['expected'], $record['range'], $record['page_count']);
    }
    $record = [];
  }
  if (preg_match('/<Link>(.*?.pdf)<\/Link>/', $line, $matches)) {
    $record['pdf'] = sprintf('%s/%s', $directory, $matches[1]);
  }
  if (preg_match('/<Range>(.*?)<\/Range>/', $line, $matches)) {
    $record['range'] = $matches[1];
    if (preg_match('/^(\d+)-(\d+)$/', $record['range'], $matches)) {
      $record['from'] = $matches[1];
      $record['to'] = $matches[2];
      $record['expected'] = ($record['to'] - $record['from']) + 1;
    }
  }
}
