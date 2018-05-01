<?php

namespace App\Http\Controllers\Admin;

use App\Firebase\DataMapper;
use App\Firebase\FirebaseConnection;
use App\Firebase\PopoMapper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use ZipArchive;

class Event extends Controller
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getOverview($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.overview.admin_event_overview_{$this->theme}", compact('meta'));
    }

    public function getManagementRegistrar($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.management.registrar.admin_event_management_registrar_{$this->theme}", compact('meta'));
    }

    public function getManagementTester($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.management.tester.admin_event_management_tester_{$this->theme}", compact('meta'));
    }

    public function getUploadParticipant($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.upload.participant.admin_event_upload_participant_{$this->theme}", compact('meta'));
    }

    public function getUploadParticipantTemplate($event)
    {
        $now      = Carbon::now();
        $filename = 'Template Peserta.csv';
        $dirFile  = base_path("public/csv/$filename");
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header("Content-length: " . filesize($dirFile));
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header("Last-Modified: {$now->toRfc7231String()}"); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        readfile($dirFile);
    }

    public function getUploadParticipantUpload(FirebaseConnection $firebase, Request $request, $event)
    {
        $file = null;
        if ($request->has('upload') && $request->file('upload')->isValid())
        {
            try
            {
                $file   = $request->file('upload');
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $reader->setSheetIndex(0);
                $spreadsheet = $reader->load($file->path());
                $spreadsheet->setActiveSheetIndex(0);
                $worksheet    = $spreadsheet->getActiveSheet();
                $c            = 2;
                $participants = [];
                $no           = $name = $gender = null;
                while ((strlen($no = trim($worksheet->getCell("A{$c}"))) > 0) && (strlen($name = trim($worksheet->getCell("B{$c}"))) > 0) && (strlen($gender = trim($worksheet->getCell("C{$c}"))) > 0) && (($gender == 'l') || ($gender == 'p')))
                {
                    $participants[$no] = compact('no', 'name', 'gender');
                    ++$c;
                }
                $firebase
                    ->getConnection()
                    ->getDatabase()
                    ->getReference(DataMapper::event(null, null, $event)['events'] . "/participant")
                    ->set($participants);

                return response()->json(PopoMapper::jsonResponse(200, 'OK', []), 200);
            }
            catch (Exception $e)
            {
                Log::error($e);

                return response()->json(PopoMapper::jsonResponse(500, 'Internal Server Error', ['Terjadi Kesalahan']), 500);
            }
            catch (\PhpOffice\PhpSpreadsheet\Exception $e)
            {
                Log::error($e);

                return response()->json(PopoMapper::jsonResponse(500, 'Internal Server Error', ['Terjadi Kesalahan']), 500);
            }
        }

        return response()->json(PopoMapper::jsonResponse(422, 'Unprocessed Entity', ['Data Tidak Valid']), 422);
    }

    public function getEvaluationReport($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.report.evaluation.admin_event_report_evaluation_{$this->theme}", compact('meta'));
    }

    public function getHealthReport($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.report.health.admin_event_report_health_{$this->theme}", compact('meta'));
    }

    public function getPublishEvaluation(Request $request, FirebaseConnection $firebase, $event)
    {
        $jEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::event(null, null, $event)['events'])
            ->getValue() ?: [];

        $pEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::preset(null, $jEvent['preset_active'])['presets'])
            ->getValue() ?: [];

        try
        {
            $queues = [];
            foreach ($pEvent['queues'] as &$dv)
            {
                foreach ($dv as &$qv)
                {
                    if ($qv != null && key_exists('participant', $qv))
                    {
                        $queues[$qv['participant']['no']] = &$qv;
                    }
                }
            }

            uasort($jEvent['participant'], function ($a, $b) use ($queues) {
                $cmp = [$a['no'] => 0, $b['no'] => 0];
                foreach ([&$a, &$b] as &$pv)
                {
                    $result = 0;
                    if (key_exists($pv['no'], $queues))
                    {
                        $queue = &$queues[$pv['no']];
                        foreach ($queue as &$kv)
                        {
                            if (key_exists('result', $kv))
                            {
                                $result += intval($kv['result']);
                            }
                        }
                    }
                    $cmp[$pv['no']] = $result;
                }
                if ($cmp[$a['no']] == $cmp[$b['no']])
                {
                    return 0;
                }

                return ($cmp[$a['no']] < $cmp[$b['no']]) ? 1 : -1;
            });

            /** @var \Carbon\Carbon $now */
            $now = \Carbon\Carbon::now();
            /** @var Spreadsheet $spreadsheet */
            $spreadsheet = new Spreadsheet();
            $filename    = "Daftar Nilai Ujian Keterampilan Bidang Olahraga SBMPTN {$now->year}";

            // Set document properties
            $spreadsheet->getProperties()->setCreator('Universitas Negeri Malang')
                ->setLastModifiedBy('Universitas Negeri Malang')
                ->setTitle('Daftar Nilai Ujian Keterampilan')
                ->setSubject('Bidang Olahraga')
                ->setDescription($filename)
                ->setKeywords('SBMPTN')
                ->setCategory('Ujian Keterampilan');

            // Set Active Sheet
            $spreadsheet->setActiveSheetIndex(0);

            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $spreadsheet->getActiveSheet()->getPageMargins()
                ->setTop(0.492126)
                ->setBottom(0.492126)
                ->setLeft(0.492126)
                ->setRight(0.492126)
                ->setHeader(0.19685)
                ->setFooter(0.19685);

            //Column Width
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(16);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(36);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(7);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(13);


            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(base_path('/public/img/logo-sbmptn.png'));
            $drawing->setResizeProportional(true);
            $drawing->setWidthAndHeight(150, 120);
            $drawing->setCoordinates('C1');
            $drawing->setOffsetX(225);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());

            //Title
            $spreadsheet->getActiveSheet()->mergeCells('A8:G8');
            $spreadsheet->getActiveSheet()->getStyle('A8')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A8')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A8', 'DAFTAR NILAI UJI KETERAMPILAN');

            //Subtitle
            $spreadsheet->getActiveSheet()->mergeCells('A9:G9');
            $spreadsheet->getActiveSheet()->getStyle('A9')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A9')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A9', 'BIDANG : SENI RUPA / SENI TARI / SENI MUSIK / OLAHRAGA');

            //Origin
            $spreadsheet->getActiveSheet()->mergeCells('A10:G10');
            $spreadsheet->getActiveSheet()->getStyle('A10')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A10')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A10', 'UNIVERSITAS :.........................................');

            //Table Header
            foreach ([
                         ['c' => 'A', 'v' => 'NO URUT'],
                         ['c' => 'B', 'v' => 'NOMOR PESERTA'],
                         ['c' => 'C', 'v' => 'NAMA PESERTA'],
                         ['c' => 'D', 'v' => 'NILAI'],
                         ['c' => 'E', 'v' => 'TIDAK HADIR'],
                         ['c' => 'F', 'v' => 'WAJAH TIDAK SESUAI FOTO PADA ABHP'],
                         ['c' => 'G', 'v' => 'KETERANGAN'],
                     ] as $c)
            {
                $spreadsheet->getActiveSheet()->getStyle("{$c['c']}12")->getFont()
                    ->setSize(11)
                    ->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle("{$c['c']}12")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $spreadsheet->getActiveSheet()->setCellValue("{$c['c']}12", $c['v']);
            }

            $leftBlock = [
                'font' => [
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ];

            $centerBlock                            = $leftBlock;
            $centerBlock['alignment']['horizontal'] = Alignment::HORIZONTAL_CENTER;


            $participantCount = count($jEvent['participant']) + 11;
            $spreadsheet->getActiveSheet()->getStyle("D13:G$participantCount")->applyFromArray($centerBlock);
            $spreadsheet->getActiveSheet()->getStyle("A13:C$participantCount")->applyFromArray($leftBlock);
            $spreadsheet->getActiveSheet()->getStyle("B13:B$participantCount")->applyFromArray($centerBlock);
            $spreadsheet->getActiveSheet()->getStyle("A12:G$participantCount")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $activeSheet = $spreadsheet->getActiveSheet();
            $startCount  = 12;
            $cL          = 0;
            foreach ($jEvent['participant'] as $pk => &$pv)
            {
                if ($pv != null)
                {
                    ++$startCount;
                    $activeSheet->setCellValue("A$startCount", ++$cL);
                    $activeSheet->setCellValue("B$startCount", $pv['no']);
                    $activeSheet->setCellValue("C$startCount", $pv['name']);
                    $queue = &$queues[$pv['no']];
                    if ($queue == null)
                    {
                        $activeSheet->setCellValue("E$startCount", 1);
                        $activeSheet->setCellValue("D$startCount", 0);
                    }
                    else
                    {
                        $result = 0;
                        foreach ($queue as &$kv)
                        {
                            if (key_exists('result', $kv))
                            {
                                $result += intval($kv['result']);
                            }
                        }
                        $activeSheet->setCellValue("D$startCount", $result);
                        if (key_exists('participant', $queue) && key_exists('same', $queue['participant']) && $queue['participant']['same'] == 0)
                        {
                            $activeSheet->setCellValue("F$startCount", 1);
                        }
                    }
                }
            }

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$filename.xlsx\"");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header("Last-Modified: {$now->toRfc7231String()}"); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save("php://output");

            return null;
        }
        catch (\Exception $e)
        {
            Log::error($e);
            if ($request->wantsJson())
            {
                return response()->json(PopoMapper::jsonResponse(500, 'Internal Server Error', ['Terjadi Kesalahan']), 500);
            }
            else
            {
                return redirect()->back()->with('cbk_msg', ['notify' => ["Terjadi Kesalahan"]]);
            }
        }
    }

    public function getPublishHealthReport(Request $request, FirebaseConnection $firebase, $event)
    {
        $jEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::event(null, null, $event)['events'])
            ->getValue() ?: [];

        $pEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::preset(null, $jEvent['preset_active'])['presets'])
            ->getValue() ?: [];

        try
        {
            /** @var \Carbon\Carbon $now */
            $now          = \Carbon\Carbon::now();
            $fileTemplate = "Template_{$now->year}.xlsx";
            $dir          = uniqid('xlsx');
            $saveDir      = base_path("/public/xlsx/compress/$dir");
            mkdir($saveDir, 0777, true);
            /** @var Spreadsheet $spreadsheet */
            $reader   = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $filename = "Daftar Nilai Kesehatan Ujian Keterampilan Bidang Olahraga SBMPTN Kolektif {$now->year}";

            $queues      = [];
            $fileNames   = [];
            $spreadsheet = $reader->load(base_path("public/xlsx/$fileTemplate"));
            $template    = $spreadsheet->getSheetByName('Template');
            foreach ($pEvent['queues'] as &$dv)
            {
                foreach ($dv as &$qv)
                {
                    if ($qv != null && key_exists('participant', $qv))
                    {
                        $queues[$qv['participant']['no']] = &$qv;
                    }
                }
            }
            /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $template */
            foreach ($jEvent['participant'] as $pk => &$pv)
            {
                if ($pv != null && key_exists($pv['no'], $queues))
                {
                    $queue = &$queues[$pv['no']];

                    $template->setCellValue("G6", $pv['name']);
                    $template->setCellValue("G7", $pv['no']);
                    $template->setCellValue("L17", "kg/m²");
                    $template->setCellValue("I18", "BMI = Berat Badan : (Tinggi Badan Berdiri)²");

                    if (key_exists('medical', $queue))
                    {
                        $process = &$queue['medical'];
                        //Anthropometric
                        $template->setCellValue("I13", key_exists('tbb', $process) ? number_format($process['tbb'], 0, ',', '.') : '');
                        $template->setCellValue("I14", key_exists('tbd', $process) ? number_format($process['tbd'], 0, ',', '.') : '');
                        $template->setCellValue("I15", key_exists('ratio', $process) ? number_format($process['ratio'], 2, ',', '.') : '');
                        $template->setCellValue("I16", key_exists('weight', $process) ? number_format($process['weight'], 0, ',', '.') : '');
                        $template->setCellValue("I17", key_exists('bmi', $process) ? number_format($process['bmi'], 2, ',', '.') : '');

                        //Posture and Gait
                        $selection = null;
                        if (key_exists('posture', $process))
                        {
                            $selection = null;
                            switch ($process['posture'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'E24';break;
                            case 'Skoliosis' :  $selection = 'E25';break;
                            case 'Kifosis' :  $selection = 'E26';break;
                            case 'Lordosis' :  $selection = 'E27';break;
                            default : $selection = null;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                            else
                            {
                                foreach (range(24, 27) as $v)
                                {
                                    $template->setCellValue("E$v", '');
                                }
                            }
                        }
                        else
                        {
                            foreach (range(24, 27) as $v)
                            {
                                $template->setCellValue("E$v", '');
                            }
                        }

                        if (key_exists('gait', $process))
                        {
                            $selection = null;
                            switch ($process['gait'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'K24';break;
                            case 'Deformitas' :  $selection = 'K25';break;
                            case 'Kelemahan' :  $selection = 'K26';break;
                            case 'Kelainan Gait' :  $selection = 'K27';break;
                            default:$selection = null;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                            else
                            {
                                foreach (range(24, 27) as $v)
                                {
                                    $template->setCellValue("K$v", '');
                                }
                            }
                        }
                        else
                        {
                            foreach (range(24, 27) as $v)
                            {
                                $template->setCellValue("K$v", '');
                            }
                        }

                        //Cardiovascular
                        $template->setCellValue("I31", key_exists('pulse', $process) ? number_format($process['pulse'], 0, ',', '.') : '');
                        $template->setCellValue("I32", key_exists('pressure', $process) ? $process['pressure'] : '');
                        $template->setCellValue("I34", key_exists('ictus', $process) ? $process['ictus'] : ' + / —');
                        $template->setCellValue("I35", key_exists('heart', $process) ? $process['heart'] : 'Normal / tidak');

                        //Pernafasan
                        $template->setCellValue("I39", key_exists('frequency', $process) ? number_format($process['frequency'], 0, ',', '.') : '');
                        $template->setCellValue("I40", key_exists('retraction', $process) ? $process['retraction'] : '+ / —');
                        $template->setCellValue("M41", key_exists('r_location', $process) ? $process['r_location'] : '');
                        $template->setCellValue("I42", key_exists('breath', $process) ? $process['breath'] : 'Normal / tidak');
                        $template->setCellValue("I43", key_exists('b_pipeline', $process) ? $process['b_pipeline'] : 'Normal/ obstruksi');

                        //Vision
                        if (key_exists('vision', $process))
                        {
                            $selection = null;
                            switch ($process['vision'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'E48';break;
                            case 'Juling' :  $selection = 'E49';break;
                            case 'Plus / Minus / Silinder' :  $selection = 'E50';break;
                            default :$selection = null;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                            else
                            {
                                foreach (range(48, 50) as $v)
                                {
                                    $template->setCellValue("E$v", '');
                                }
                            }
                        }
                        else
                        {
                            foreach (range(48, 50) as $v)
                            {
                                $template->setCellValue("E$v", '');
                            }
                        }

                        if (key_exists('hearing', $process))
                        {
                            $selection = null;
                            switch ($process['hearing'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'K48';break;
                            case 'Tuli' :  $selection = 'K49';break;
                            case 'Serumen Obstruktif' :  $selection = 'K50';break;
                                default:$selection = null;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                            else
                            {
                                foreach (range(48, 50) as $v)
                                {
                                    $template->setCellValue("K$v", '');
                                }
                            }
                        }
                        else
                        {
                            foreach (range(48, 50) as $v)
                            {
                                $template->setCellValue("K$v", '');
                            }
                        }
                        if (key_exists('verbal', $process))
                        {
                            $selection = null;
                            switch ($process['verbal'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'E54';break;
                            case 'Latah / Gagap' :  $selection = 'E55';break;
                            case 'Tuna Wicara' :  $selection = 'E56';break;
                            default:$selection = null;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                            else
                            {
                                foreach (range(54, 56) as $v)
                                {
                                    $template->setCellValue("E$v", '');
                                }
                            }
                        }
                        else
                        {
                            foreach (range(54, 56) as $v)
                            {
                                $template->setCellValue("E$v", '');
                            }
                        }

                        //Conclusion
                        if (key_exists('conclusion', $process))
                        {
                            $selection = null;
                            switch ($process['conclusion'])
                            {//@formatter:off
                            case 'Disarankan' : $selection = 'E61';break;
                            case 'Tidak Disarankan' :  $selection = 'E62';break;
                            case true : $selection = 'E61';break;
                            case false :  $selection = 'E62';break;
                                default : $selection =null;
                            }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                            else
                            {
                                foreach (range(61, 62) as $v)
                                {
                                    $template->setCellValue("E$v", '');
                                }
                            }
                        }
                        else
                        {
                            foreach (range(61, 62) as $v)
                            {
                                $template->setCellValue("E$v", '');
                            }
                        }
                    }
                    else
                    {
                        $template->setCellValue("I13", '');
                        $template->setCellValue("I14", '');
                        $template->setCellValue("I15", '');
                        $template->setCellValue("I16", '');
                        $template->setCellValue("I17", '');
                        foreach (range(24, 27) as $v)
                        {
                            $template->setCellValue("E$v", '');
                        }
                        foreach (range(24, 27) as $v)
                        {
                            $template->setCellValue("K$v", '');
                        }
                        $template->setCellValue("I31", '');
                        $template->setCellValue("I32", '');
                        $template->setCellValue("I34", ' + / —');
                        $template->setCellValue("I35", 'Normal / tidak');
                        $template->setCellValue("I39", '');
                        $template->setCellValue("I40", '+ / —');
                        $template->setCellValue("M41", '');
                        $template->setCellValue("I42", 'Normal / tidak');
                        $template->setCellValue("I43", 'Normal/ obstruksi');
                        foreach (range(48, 50) as $v)
                        {
                            $template->setCellValue("E$v", '');
                        }
                        foreach (range(48, 50) as $v)
                        {
                            $template->setCellValue("K$v", '');
                        }
                        foreach (range(54, 56) as $v)
                        {
                            $template->setCellValue("E$v", '');
                        }
                        foreach (range(61, 62) as $v)
                        {
                            $template->setCellValue("E$v", '');
                        }
                    }

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save($saveDir . "/${pv['no']}.xlsx");
                    array_push($fileNames, "/${pv['no']}.xlsx");
                }
            }
            $this->compress_and_packing($filename, $saveDir, $fileNames);

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/zip');
            header("Content-Disposition: attachment;filename=\"$filename.zip\"");
            header("Content-length: " . filesize("$saveDir/$filename.zip"));
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header("Last-Modified: {$now->toRfc7231String()}"); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            readfile("$saveDir/$filename.zip");
        }
        catch (\Exception $e)
        {
            Log::error($e);
            if ($request->wantsJson())
            {
                return response()->json(PopoMapper::jsonResponse(500, 'Internal Server Error', ['Terjadi Kesalahan']), 500);
            }
            else
            {
                return redirect()->back()->with('cbk_msg', ['notify' => ["Terjadi Kesalahan"]]);
            }
        }
    }

    public function getPublishHealthReportOnce(FirebaseConnection $firebase, Request $request, $event)
    {
        $jEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::event(null, null, $event)['events'])
            ->getValue() ?: [];

        $pEvent      = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::preset(null, $jEvent['preset_active'])['presets'])
            ->getValue() ?: [];
        $participant = $request->get('participant', null);
        $spreadsheet = null;
        try
        {
            /** @var \Carbon\Carbon $now */
            $now          = \Carbon\Carbon::now();
            $fileTemplate = "Template_{$now->year}.xlsx";
            /** @var Spreadsheet $spreadsheet */
            $reader   = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $filename = "Daftar Nilai Kesehatan Ujian Keterampilan Bidang Olahraga SBMPTN Individu {$now->year}";

            $queues    = [];
            $fileNames = [];
            foreach ($pEvent['queues'] as &$dv)
            {
                foreach ($dv as &$qv)
                {
                    if ($qv != null && key_exists('participant', $qv) && $qv['participant']['no'] == $participant)
                    {
                        $queues[$qv['participant']['no']] = &$qv;
                    }
                }
            }
            /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $template */
            foreach ($jEvent['participant'] as $pk => &$pv)
            {
                if ($pv != null && key_exists($pv['no'], $queues))
                {
                    $queue       = &$queues[$pv['no']];
                    $spreadsheet = $reader->load(base_path("public/xlsx/$fileTemplate"));
                    $template    = $spreadsheet->getSheetByName('Template');

                    $template->setCellValue("G6", $pv['name']);
                    $template->setCellValue("G7", $pv['no']);
                    $template->setCellValue("L17", "kg/m²");
                    $template->setCellValue("I18", "BMI = Berat Badan : (Tinggi Badan Berdiri)²");

                    if (key_exists('medical', $queue))
                    {
                        $process = &$queue['medical'];
                        //Anthropometric
                        $template->setCellValue("I13", key_exists('tbb', $process) ? number_format($process['tbb'], 0, ',', '.') : '');
                        $template->setCellValue("I14", key_exists('tbd', $process) ? number_format($process['tbd'], 0, ',', '.') : '');
                        $template->setCellValue("I15", key_exists('ratio', $process) ? number_format($process['ratio'], 2, ',', '.') : '');
                        $template->setCellValue("I16", key_exists('weight', $process) ? number_format($process['weight'], 0, ',', '.') : '');
                        $template->setCellValue("I17", key_exists('bmi', $process) ? number_format($process['bmi'], 2, ',', '.') : '');

                        //Posture and Gait
                        $selection = null;
                        if (key_exists('posture', $process))
                        {
                            $selection = null;
                            switch ($process['posture'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'E24';break;
                            case 'Skoliosis' :  $selection = 'E25';break;
                            case 'Kifosis' :  $selection = 'E26';break;
                            case 'Lordosis' :  $selection = 'E27';break;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                        }

                        if (key_exists('gait', $process))
                        {
                            $selection = null;
                            switch ($process['gait'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'K24';break;
                            case 'Deformitas' :  $selection = 'K25';break;
                            case 'Kelemahan' :  $selection = 'K26';break;
                            case 'Kelainan Gait' :  $selection = 'K27';break;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                        }

                        //Cardiovascular
                        $template->setCellValue("I31", key_exists('pulse', $process) ? number_format($process['pulse'], 0, ',', '.') : '');
                        $template->setCellValue("I32", key_exists('pressure', $process) ? $process['pressure'] : '');
                        $template->setCellValue("I34", key_exists('ictus', $process) ? $process['ictus'] : ' + / —');
                        $template->setCellValue("I35", key_exists('heart', $process) ? $process['heart'] : 'Normal / tidak');

                        //Pernafasan
                        $template->setCellValue("I39", key_exists('frequency', $process) ? number_format($process['frequency'], 0, ',', '.') : '');
                        $template->setCellValue("I40", key_exists('retraction', $process) ? $process['retraction'] : '+ / —');
                        $template->setCellValue("M41", key_exists('r_location', $process) ? $process['r_location'] : '');
                        $template->setCellValue("I42", key_exists('breath', $process) ? $process['breath'] : 'Normal / tidak');
                        $template->setCellValue("I43", key_exists('b_pipeline', $process) ? $process['b_pipeline'] : 'Normal/ obstruksi');

                        //Vision
                        if (key_exists('vision', $process))
                        {
                            $selection = null;
                            switch ($process['vision'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'E48';break;
                            case 'Juling' :  $selection = 'E49';break;
                            case 'Plus / Minus / Silinder' :  $selection = 'E50';break;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                        }
                        if (key_exists('hearing', $process))
                        {
                            $selection = null;
                            switch ($process['hearing'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'K48';break;
                            case 'Tuli' :  $selection = 'K49';break;
                            case 'Serumen Obstruktif' :  $selection = 'K50';break;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                        }
                        if (key_exists('verbal', $process))
                        {
                            $selection = null;
                            switch ($process['verbal'])
                            {//@formatter:off
                            case 'Normal' : $selection = 'E54';break;
                            case 'Latah / Gagap' :  $selection = 'E55';break;
                            case 'Tuna Wicara' :  $selection = 'E56';break;
                        }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                        }

                        //Conclusion
                        if (key_exists('conclusion', $process))
                        {
                            $selection = null;
                            switch ($process['conclusion'])
                            {//@formatter:off
                            case 'Disarankan' : $selection = 'E61';break;
                            case 'Tidak Disarankan' :  $selection = 'E62';break;
                            case true : $selection = 'E61';break;
                            case false :  $selection = 'E62';break;
                            }//@formatter:on
                            if ($selection != null)
                            {
                                $template->setCellValue($selection, '✓');
                            }
                        }
                    }


                }
            }

            if ($spreadsheet == null)
            {
                throw new \Exception();
            }
            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$filename.xlsx\"");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header("Last-Modified: {$now->toRfc7231String()}"); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');

            return null;
        }
        catch (\Exception $e)
        {
            Log::error($e);
            if ($request->wantsJson())
            {
                return response()->json(PopoMapper::jsonResponse(500, 'Internal Server Error', ['Terjadi Kesalahan']), 500);
            }
            else
            {
                return redirect()->back()->with('cbk_msg', ['notify' => ["Terjadi Kesalahan"]]);
            }
        }
    }

    public function getPublishHealth(Request $request, FirebaseConnection $firebase, $event)
    {
        $jEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::event(null, null, $event)['events'])
            ->getValue() ?: [];

        $pEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::preset(null, $jEvent['preset_active'])['presets'])
            ->getValue() ?: [];

        try
        {
            /** @var \Carbon\Carbon $now */
            $now = \Carbon\Carbon::now();
            /** @var Spreadsheet $spreadsheet */
            $spreadsheet = new Spreadsheet();
            $filename    = "Daftar Kesehatan Ujian Keterampilan Bidang Olahraga SBMPTN {$now->year}";

            // Set document properties
            $spreadsheet->getProperties()->setCreator('Universitas Negeri Malang')
                ->setLastModifiedBy('Universitas Negeri Malang')
                ->setTitle('Daftar Nilai Ujian Keterampilan')
                ->setSubject('Bidang Olahraga')
                ->setDescription($filename)
                ->setKeywords('SBMPTN')
                ->setCategory('Ujian Keterampilan');

            // Set Active Sheet
            $spreadsheet->setActiveSheetIndex(0);

            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $spreadsheet->getActiveSheet()->getPageMargins()
                ->setTop(0.492126)
                ->setBottom(0.492126)
                ->setLeft(0.492126)
                ->setRight(0.492126)
                ->setHeader(0.19685)
                ->setFooter(0.19685);

            //Column Width
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(16);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(7);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(13);


            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(base_path('/public/img/logo-sbmptn.png'));
            $drawing->setResizeProportional(true);
            $drawing->setWidthAndHeight(150, 120);
            $drawing->setCoordinates('C1');
            $drawing->setOffsetX(225);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());

            //Title
            $spreadsheet->getActiveSheet()->mergeCells('A8:G8');
            $spreadsheet->getActiveSheet()->getStyle('A8')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A8')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A8', 'DAFTAR NILAI UJI KETERAMPILAN');

            //Subtitle
            $spreadsheet->getActiveSheet()->mergeCells('A9:G9');
            $spreadsheet->getActiveSheet()->getStyle('A9')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A9')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A9', 'BIDANG : SENI RUPA / SENI TARI / SENI MUSIK / OLAHRAGA');

            //Origin
            $spreadsheet->getActiveSheet()->mergeCells('A10:G10');
            $spreadsheet->getActiveSheet()->getStyle('A10')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A10')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A10', 'UNIVERSITAS :.........................................');

            //Table Header
            foreach ([
                         ['c' => 'A', 'v' => 'NO URUT'],
                         ['c' => 'B', 'v' => 'NOMOR PESERTA'],
                         ['c' => 'C', 'v' => 'NAMA PESERTA'],
                         ['c' => 'D', 'v' => 'KESIMPULAN'],
                         ['c' => 'E', 'v' => 'TIDAK HADIR'],
                         ['c' => 'F', 'v' => 'WAJAH TIDAK SESUAI FOTO PADA ABHP'],
                         ['c' => 'G', 'v' => 'KETERANGAN'],
                     ] as $c)
            {
                $spreadsheet->getActiveSheet()->getStyle("{$c['c']}12")->getFont()
                    ->setSize(11)
                    ->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle("{$c['c']}12")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $spreadsheet->getActiveSheet()->setCellValue("{$c['c']}12", $c['v']);
            }

            $leftBlock = [
                'font' => [
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ];

            $centerBlock                            = $leftBlock;
            $centerBlock['alignment']['horizontal'] = Alignment::HORIZONTAL_CENTER;

            $queues = [];
            foreach ($pEvent['queues'] as &$dv)
            {
                foreach ($dv as &$qv)
                {
                    if ($qv != null && key_exists('participant', $qv))
                    {
                        $queues[$qv['participant']['no']] = &$qv;
                    }
                }
            }
            $participantCount = count($jEvent['participant']) + 11;
            $spreadsheet->getActiveSheet()->getStyle("D13:G$participantCount")->applyFromArray($centerBlock);
            $spreadsheet->getActiveSheet()->getStyle("A13:C$participantCount")->applyFromArray($leftBlock);
            $spreadsheet->getActiveSheet()->getStyle("B13:B$participantCount")->applyFromArray($centerBlock);
            $spreadsheet->getActiveSheet()->getStyle("A12:G$participantCount")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $activeSheet = $spreadsheet->getActiveSheet();
            $startCount  = 12;
            foreach ($jEvent['participant'] as $pk => &$pv)
            {
                if ($pv != null)
                {
                    ++$startCount;
                    $activeSheet->setCellValue("A$startCount", $pk);
                    $activeSheet->setCellValue("B$startCount", $pv['no']);
                    $activeSheet->setCellValue("C$startCount", $pv['name']);
                    $queue = &$queues[$pv['no']];
                    if ($queue == null)
                    {
                        $activeSheet->setCellValue("E$startCount", 1);
                        $activeSheet->setCellValue("D$startCount", 'Tidak Disarankan');
                    }
                    else
                    {
                        if (key_exists('medical', $queue))
                        {
                            $activeSheet->setCellValue("D$startCount", (key_exists('conclusion', $queue['medical']) && $queue['medical']['conclusion']) ? 'Disarankan' : 'Tidak Disarankan');
                        }
                        else
                        {
                            $activeSheet->setCellValue("D$startCount", 'Tidak Disarankan');
                        }
                        if (key_exists('participant', $queue) && key_exists('same', $queue['participant']) && $queue['participant']['same'] == 0)
                        {
                            $activeSheet->setCellValue("F$startCount", 1);
                        }
                    }
                }
            }

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$filename.xlsx\"");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header("Last-Modified: {$now->toRfc7231String()}"); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save("php://output");
        }
        catch (\Exception $e)
        {
            Log::error($e);
            if ($request->wantsJson())
            {
                return response()->json(PopoMapper::jsonResponse(500, 'Internal Server Error', ['Terjadi Kesalahan']), 500);
            }
            else
            {
                return redirect()->back()->with('cbk_msg', ['notify' => ["Terjadi Kesalahan"]]);
            }
        }
    }

    private function compress_and_packing($filename, $path, $data)
    {
        $zip      = new ZipArchive();
        $filename = "$path/$filename.zip";

        if ($zip->open($filename, ZipArchive::CREATE) !== true)
        {
            exit("cannot open <$filename>\n");
        }
        foreach ($data as &$datum)
        {
            $zip->addFile("$path/$datum", $datum);
        }
        $zip->close();
    }
}
