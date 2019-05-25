<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

class Elfinder_lib extends CI_Controller {

    public function index()
    {
        $this->manager();
    }

    public function manager()
    {
        $this->load->helper('url');
        $data['connector'] = base_url() . 'elfinder_lib/connector';
        $this->load->view('elfinder', $data);
    }

    public function connector()
    {
        //BEGIN AWS CONFIG HERE!!!!!!!!!!!!!!!
        $aws_config = [
            "key" => "",
            "secret" => "",
            "region" => "",
            "bucket" => "",
            "prefix" => $_GET["folder"],//prefix of listed images, it will be appended to your AWS bucket address. eg. "images" or "some/path/"
            "version" => "latest",
            "acl" => 'public-read',
            "private-acl" => 'private',
            "StorageClass" => 'STANDARD',
        ];
        //END AWS CONFIG*******************

        $aws_url = "http://" . $aws_config["bucket"] . ".s3." . $aws_config["region"] . ".amazonaws.com";
        $client = new S3Client(
            Array ( "driver" => "s3",
                "key" => $aws_config["key"],
                "secret" => $aws_config["secret"],
                "region" => $aws_config["region"],
                "bucket" => $aws_config["bucket"],
                "url" => $aws_url,
                "version" => $aws_config["version"],
                "acl" => $aws_config["acl"],
                "private-acl" => $aws_config["private-acl"],
                "StorageClass" => $aws_config["StorageClass"],
                "credentials" => Array (
                    "key" => $aws_config["key"],
                    "secret" => $aws_config["secret"]
                )
            )
        );
        $adapter = new AwsS3Adapter($client, $aws_config["bucket"], $aws_config["prefix"],[
            "version" => $aws_config["version"],
            'ACL' => $aws_config["acl"],
            "private-acl" => $aws_config["private-acl"],
            "StorageClass" => $aws_config["StorageClass"]
        ]);
        $filesystem = new Filesystem($adapter, Array ( "url" => $aws_url ));

        $opts = array(
            // 'debug' => true,
            'roots' => [
                [
                    'driver' => 'Flysystem',
                    'alias' => 'BIQDev.com',//Change to anything you like
                    'filesystem' => $filesystem,
                    'URL' => $aws_url,
                    'tmbURL' => 'self'
                ]
            ],
            'bind' => array(
                'upload.pre' => array( $this, 'preCheck' )
            )
        );

        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();

    }

    public function preCheck($cmd, &$args, $elfinder, $volume)
    {
        $max_file_size_mb = 0.1;//TODO: Seting di sini untuk batas per upload dalam 'MB'
        $max_file_size = $max_file_size_mb * 1024 * 1024;

        $max_capacity_mb = 5;//TODO: Seting di sini untuk batas total semua file dalam 'MB'
        $max_capacity = $max_capacity_mb * 1024 * 1024;

        $volume_size = $volume->size($volume->root())['size'];
        $new_file_size = $args["FILES"]["upload"]["size"][0];
        $volume_size_final = $volume_size + $new_file_size;
        $isFull = ( $volume_size_final > $max_capacity);

        if( $new_file_size > $max_file_size ) {
            return array(
                'preventexec' => true,
                'results' => array('error' => 'Upload gagal, maksimum upload perfile: '.$max_file_size_mb.'MB' )
            );
        }

        $type = explode('/',$args["FILES"]["upload"]["type"][0]);
        if( strtolower($type[0]) !== 'image') {
            return array(
                'preventexec' => true,
                'results' => array('error' => 'Upload gagal, selain gambar tidak diizinkan. Anda mengupload type: '.$type[0].'/'.$type[1] )
            );
        }

        if( $new_file_size > $max_file_size ) {
            return array(
                'preventexec' => true,
                'results' => array('error' => 'Upload gagal, maksimum upload perfile: '.$max_file_size_mb.'MB' )
            );
        }

        if ($cmd === 'upload' && $isFull) {
            return array(
                'preventexec' => true,
                'results' => array('error' => 'Upload Gagal, anda melebihi kapasitas penyimpanan file. Maksimum kapasitas total: '.$max_capacity_mb.'MB' )
            );
        }
    }

}
