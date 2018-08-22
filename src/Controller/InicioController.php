<?php
namespace App\Controller;

use App\Entity\Task;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\Tests\Fixtures\Article;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends FOSRestController {

    //    ========= Listar =========

    /**
     * @Rest\Post("/api/Listar")
     */

    public function Listar(Request $request) {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository("App:Task")->listar();
    }

//    ========= Registrar =========

    /**
     * @Rest\Post("/api/Insert")
     */

    public function Insert(Request $request) {

        $msn = "Se Agrego Correactamete";
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(),true);
        $form = $data['data'];

        $task = new Task();
        $task->setTitulo($form["title"]);
        $task->setDescripcion($form["description"]);

        $em->persist($task);
        $em->flush();

        return [
            'guardado' => true,
            'msg' => $msn,
        ];
    }

    //    ========= Eliminar =========

    /**
     * @Rest\Post("/api/Delete/{id}")
     */

    public function Delete($id){
        try {
            $msn = "Se Elimino Correactamete";
            $em = $this->getDoctrine()->getManager();
            $dle = $em->getRepository("App:Task")->find($id);

            if($dle !== null) {
                $em->remove($dle);
            }
            $em->flush();
        } catch (\Exception $e) {
            $borrado = false;
        }

        return [
            'borrado' => true,
            'msg' => $msn,
        ];
    }


    //    ========= Actualizar =========

    /**
     * @Rest\Post("/api/Update")
     */

    public function Update(Request $request){
        $msn = "Se Actualizo Correactamete";
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(),true);
        $form = $data['data'];

        foreach ($form as $datas){

            $task = $em->getRepository('App:Task')->find($datas["Id"]);
            $task->setTitulo($datas["title"]);
            $task->setDescripcion($datas["description"]);
            $em->persist($task);
        }

        $em->flush();
        return $msn;
    }
}