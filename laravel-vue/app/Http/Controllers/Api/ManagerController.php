<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagerController extends Controller {

    /**
     * CENAS QUE O MANAGER PODE FAZER:
     * - supervisionar os cooks e deliverymen
     * - mostrar na dashboard a lista de todos os empregados e o estado deles (se estão a trabalhar e em quê)
     * - mostrar a lista das ordens ativas (que não foi entregue ou cancelada) com um resumo de cada (id, status, quem está a tratar)
     * - um manager pode cancelar uma ordem que não foi entregue
     * - tem acesso a mais informações acerca da empresa (QUAIS?)
     * - gerir produtos (CRUD)
     * - bloquear utilizadores (exceto ele mesmo)
     * - criar/atualizar/apagar empregados
     * - apagar um cliente
     */
}
