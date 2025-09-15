<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelo\chesscomJSON\ChesscomProfileApiConverter.php

require_once __DIR__ . '/../modelo/ChesscomProfile.php';
require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomProfileApiModel.php';

class ChesscomProfileApiConverter
{
    public static function toChesscomProfile(ChesscomProfileApiModel $apiModel): ChesscomProfile
    {
        return new ChesscomProfile(
            $apiModel->player_id,
            $apiModel->chesscom_id,
            $apiModel->participante_id,
            $apiModel->url,
            $apiModel->username,
            $apiModel->followers,
            $apiModel->country,
            $apiModel->last_online,
            $apiModel->joined,
            $apiModel->status,
            $apiModel->is_streamer,
            $apiModel->verified,
            $apiModel->league,
            $apiModel->fecha_alta,
            $apiModel->fecha_modificacion
        );
    }

    public static function toChesscomProfileApiModel(ChesscomProfile $profile): ChesscomProfileApiModel
    {
        $apiModel = new ChesscomProfileApiModel(
            $profile->player_id,
            $profile->chesscom_id,
            $profile->participante_id,
            $profile->url,
            $profile->username
        );
        $apiModel->followers = $profile->followers;
        $apiModel->country = $profile->country;
        $apiModel->last_online = $profile->last_online;
        $apiModel->joined = $profile->joined;
        $apiModel->status = $profile->status;
        $apiModel->is_streamer = $profile->is_streamer;
        $apiModel->verified = $profile->verified;
        $apiModel->league = $profile->league;
        $apiModel->fecha_alta = $profile->fecha_alta;
        $apiModel->fecha_modificacion = $profile->fecha_modificacion;
        return $apiModel;
    }
}