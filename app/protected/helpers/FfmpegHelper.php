<?php

/*1，获取视频的信息
   ffmpeg -i video.avi

2，将图片序列合成视频
   ffmpeg -f image2 -i image%d.jpg video.mpg
   上面的命令会把当前目录下的图片（名字如：image1.jpg, image2.jpg, 等...）合并成video.mpg

3，将视频分解成图片序列
   ffmpeg -i video.mpg image%d.jpg
   上面的命令会生成image1.jpg, image2.jpg, ...
   支持的图片格式有：PGM, PPM, PAM, PGMYUV, JPEG, GIF, PNG, TIFF, SGI

4，为视频重新编码以适合在iPod/iPhone上播放
   ffmpeg -i source_video.avi input -acodec aac -ab 128kb -vcodec mpeg4 -b 1200kb -mbd 2 -flags +4mv+trell -aic 2 -cmp 2 -subcmp 2 -s 320x180 -title X final_video.mp4
   说明：
       * 源视频：source_video.avi
* 音频编码：aac
* 音频位率：128kb/s
* 视频编码：mpeg4
* 视频位率：1200kb/s
* 视频尺寸：320 X 180
* 生成的视频：final_video.mp4

5，为视频重新编码以适合在PSP上播放
   ffmpeg -i source_video.avi -b 300 -s 320x240 -vcodec xvid -ab 32 -ar 24000 -acodec aac final_video.mp4
   说明：
       * 源视频：source_video.avi
* 音频编码：aac
* 音频位率：32kb/s
* 视频编码：xvid
* 视频位率：1200kb/s
* 视频尺寸：320 X 180
* 生成的视频：final_video.mp4

6，从视频抽出声音，并存为Mp3
   ffmpeg -i source_video.avi -vn -ar 44100 -ac 2 -ab 192 -f mp3 sound.mp3
   说明：
       * 源视频：source_video.avi
* 音频位率：192kb/s
* 输出格式：mp3
* 生成的声音：sound.mp3

7，将wav文件转成Mp3
   ffmpeg -i son_origine.avi -vn -ar 44100 -ac 2 -ab 192 -f mp3 son_final.mp3

8，将.avi视频转成.mpg
   ffmpeg -i video_origine.avi video_finale.mpg

9，将.mpg转成.avi
   ffmpeg -i video_origine.mpg video_finale.avi

10，将.avi转成gif动画（未压缩）
   ffmpeg -i video_origine.avi gif_anime.gif

11，合成视频和音频
   ffmpeg -i son.wav -i video_origine.avi video_finale.mpg

12，将.avi转成.flv
   ffmpeg -i video_origine.avi -ab 56 -ar 44100 -b 200 -r 15 -s 320x240 -f flv video_finale.flv

13，将.avi转成dv
   ffmpeg -i video_origine.avi -s pal -r pal -aspect 4:3 -ar 48000 -ac 2 video_finale.dv
   或者：
   ffmpeg -i video_origine.avi -target pal-dv video_finale.dv

14，将.avi压缩成divx
   ffmpeg -i video_origine.avi -s 320x240 -vcodec msmpeg4v2 video_finale.avi

15，将Ogg Theora压缩成Mpeg dvd
   ffmpeg -i film_sortie_cinelerra.ogm -s 720x576 -vcodec mpeg2video -acodec mp3 film_terminate.mpg

16，将.avi压缩成SVCD mpeg2
   NTSC格式：
      ffmpeg -i video_origine.avi -target ntsc-svcd video_finale.mpg
   PAL格式：
      ffmpeg -i video_origine.avi -target pal-svcd video_finale.mpg

17，将.avi压缩成VCD mpeg2
   NTSC格式：
      ffmpeg -i video_origine.avi -target ntsc-vcd video_finale.mpg
   PAL格式：
      ffmpeg -i video_origine.avi -target pal-vcd video_finale.mpg

18，多通道编码
   ffmpeg -i fichierentree -pass 2 -passlogfile ffmpeg2pass fichiersortie-2

19，从flv提取mp3
   ffmpeg -i source.flv -ab 128k dest.mp3
*/

class FfmpegHelper extends CFileHelper
{
    public function convertFromAmrToMp3($from, $to = null)
    {
        $to = $to ? $to : str_replace('.amr', '.mp3', $from);
        $amr = FileHelper::getPathPhysical($from);
        $mp3 = FileHelper::getPathPhysical($to);

        /*echo "{$amr}\n{$mp3}\n\n\n";
        exit;*/

        $command = sprintf('ffmpeg -i %s %s', $amr, $mp3);

        //echo $command;exit;
        echo exec($command);
        return $to;

    }

    public function convertFromMp3ToAmr($from, $to)
    {
        $from = FileHelper::getPathPhysical($from);
        $to = FileHelper::getPathPhysical($to);

        $command = sprintf('ffmpeg -i %s -ac 1 -ar 8000 %s', $from, $to);

        echo exec($command);
    }

    public function changeSpeed($from, $atempo = 1, $to = null)
    {
        $from = FileHelper::getPathPhysical($from);
        $to = $to ? $to : $from;

        $command = sprintf('ffmpeg -i %s -filter:a "atempo=%s" -vn %s', $from, $atempo, $to);
        echo exec($command);
    }

    /**
     * 转换并返回播放地址
     * @param $weixin_media_id
     * @return string
     */
    public static function audio($weixin_media_id)
    {
        $model = WeixinMedia::model()->findByPk($weixin_media_id);

        if ($model) {
            if (!$model->mp3) {

                $result = self::convertFromAmrToMp3($model->path);
                if ($result) {
                    $model->mp3 = $result;
                    $model->save();
                }
            }
            return FileHelper::getPathAbsolute($model->mp3);
        }
    }

}