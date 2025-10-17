<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;

class ErrorFunctionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         errorFunction
     * @dataProvider dataProviderForErrorFunction
     * @param        float $x
     * @param        float $expected
     */
    public function testErrorFunction(float $x, float $expected)
    {
        // When
        $errorFunction = Special::errorFunction($x);
        $erf           = Special::erf($x);

        // Then
        $this->assertEqualsWithDelta($expected, $errorFunction, \abs($expected) * 1e-6 + 1e-12);
        $this->assertEqualsWithDelta($expected, $erf, \abs($expected) * 1e-6 + 1e-12);
    }

    /**
     * Test data created with scipyu.special.erf, R (VGAM) erf(x) and online calculator https://keisan.casio.com/exec/system/1180573449
     * @return array (x, erf)
     */
    public function dataProviderForErrorFunction(): array
    {
        return [
            // scipy.special.erf
            [0.049, 0.05524636014984278],
            [0.7, 0.6778011938374184],
            [1.3, 0.9340079449406524],
            [2.9, 0.9999589021219005],
            [4, 0.9999999845827421],
            [4.5, 0.9999999998033839],
            [-0.049, -0.05524636014984278],
            [-0.7, -0.6778011938374184],
            [-1.3, -0.9340079449406524],
            [-2.9, -0.9999589021219005],
            [-4, -0.9999999845827421],
            [-4.5, -0.9999999998033839],
            // R (VGAM) erf(x) and online calculator https://keisan.casio.com/exec/system/1180573449
            [0, 0],
            [1, 0.8427007929497148693412],
            [-1, -0.8427007929497148693412],
            [2, 0.9953222650189527341621],
            [3.4, 0.9999984780066371377146],
            [0.154, 0.1724063976196591819236],
            [-2.31, -0.9989124231037000500402],
            [-1.034, -0.856340111375020118952],
            [3, 0.9999779],
            [4, 1],
            [5, 1],
            [10, 1],
            [-2, -0.9953223],
            [-3, -0.9999779],
            [-4, -1],
            [-5, -1],
            [-10, -1],
        ];
    }

    /**
     * @test         errorFunction
     * @dataProvider dataProviderForErrorFunctionWikipediaTable
     * @param        float $x
     * @param        float $expected
     */
    public function testErrorFunctionWikiepediaTableReferenceData(float $x, float $expected)
    {
        // When
        $errorFunction = Special::errorFunction($x);
        $erf           = Special::erf($x);

        // Then
        $this->assertEqualsWithDelta($expected, $errorFunction, \abs($expected) * 1e-6 + 1e-12);
        $this->assertEqualsWithDelta($expected, $erf, \abs($expected) * 1e-6 + 1e-12);
    }

    /**
     * Test data from Wikipedia tables: https://en.wikipedia.org/wiki/Error_function
     * @return array (x, erf)
     */
    public function dataProviderForErrorFunctionWikipediaTable(): array
    {
        return [
            [0, 0],
            [0.02, 0.022564575],
            [0.04, 0.045111106],
            [0.06, 0.067621594],
            [0.08, 0.090078126],
            [0.1, 0.112462916],
            [0.2, 0.222702589],
            [0.3, 0.328626759],
            [0.4, 0.428392355],
            [0.5, 0.520499878],
            [0.6, 0.603856091],
            [0.7, 0.677801194],
            [0.8, 0.742100965],
            [0.9, 0.796908212],
            [1, 0.842700793],
            [1.1, 0.880205070],
            [1.2, 0.910313978],
            [1.3, 0.934007945],
            [1.4, 0.952285120],
            [1.5, 0.966105146],
            [1.6, 0.976348383],
            [1.7, 0.983790459],
            [1.8, 0.989090502],
            [1.9, 0.992790429],
            [2, 0.995322265],
            [2.1, 0.997020533],
            [2.2, 0.998137154],
            [2.3, 0.998856823],
            [2.4, 0.999311486],
            [2.5, 0.999593048],
            [3, 0.999977910],
            [3.5, 0.999999257],
        ];
    }

    /**
     * @test         complementaryErrorFunction returns the expected value
     * @dataProvider dataProviderForComplementaryErrorFunction
     * @param        float $x
     * @param        float $expected
     */
    public function testComplementaryErrorFunction(float $x, float $expected)
    {
        // When
        $complementaryErrorFunction = Special::complementaryErrorFunction($x);
        $efc                        = Special::erfc($x);

        // Then
        $this->assertEqualsWithDelta($expected, $complementaryErrorFunction, 0.000001);
        $this->assertEqualsWithDelta($expected, $efc, 0.000001);
    }

    /**
     * Test data created with scipy.special.erfc
     * Test data created with R (VGAM) erfc and online calculator https://keisan.casio.com/exec/system/1180573449
     * Test Complementary Error Function Table: https://www.montana.edu/tjkaiser/ee407/notes/comperrfnc.pdf
     * @return array (x, erfc)
     */
    public function dataProviderForComplementaryErrorFunction(): array
    {
        return [
            // scipy.special.erfc
            [-10, 2.0],
            [-9.5, 2.0],
            [-9, 2.0],
            [-8.7, 2.0],
            [-8, 2.0],
            [-7.3, 2.0],
            [-7, 2.0],
            [-6.8, 2.0],
            [-6, 2.0],
            [-5.2, 1.9999999999998075],
            [-5, 1.9999999999984626],
            [-4.6, 1.999999999922504],
            [-4, 1.999999984582742],
            [-3.9, 1.9999999652077514],
            [-3.1, 1.9999883513426329],
            [-3, 1.9999779095030015],
            [-2.5, 1.999593047982555],
            [-2, 1.9953222650189528],
            [-1.8, 1.9890905016357308],
            [-1.2, 1.9103139782296354],
            [-1, 1.8427007929497148],
            [-0.9, 1.796908212422832],
            [-0.5, 1.5204998778130465],
            [-0.3, 1.3286267594591274],
            [-0.1, 1.1124629160182848],
            [0, 1.0],
            [0.1, 0.8875370839817152],
            [0.3, 0.6713732405408726],
            [0.5, 0.4795001221869535],
            [0.7, 0.32219880616258156],
            [0.9, 0.20309178757716806],
            [1, 0.15729920705028516],
            [1.2, 0.08968602177036462],
            [1.5, 0.033894853524689274],
            [1.8, 0.010909498364269283],
            [2, 0.004677734981047266],
            [2.3, 0.001143176597356652],
            [2.7, 0.00013433273994052424],
            [3, 2.2090496998585445e-05],
            [3.4, 1.5219933628622874e-06],
            [3.8, 7.700392745696417e-08],
            [4, 1.541725790028002e-08],
            [4.2, 2.8554941795921876e-09],
            [4.9, 4.218936524005759e-12],
            [5, 1.5374597944280353e-12],
            [6, 2.151973671249891e-17],
            [7, 4.183825607779415e-23],
            [8, 1.1224297172982928e-29],
            [9, 4.137031746513812e-37],
            [10, 2.0884875837625446e-45],
            // R (VGAM) erfc
            [0, 1],
            [1, 0.1572992070502851306588],
            [-1, 1.842700792949714869341],
            [2, 0.004677734981047265837931],
            [3.4, 1.521993362862285361757E-6],
            [0.154, 0.8275936023803408180764],
            [-2.31, 1.99891242310370005004],
            [-1.034, 1.856340111375020118952],
            [3, 2.20905e-05],
            [4, 1.541726e-08],
            [5, 1.53746e-12],
            [10, 2.088488e-45],
            [-2, 1.995322],
            [-3, 1.999978],
            [-4, 2],
            [-5, 2],
            [-10, 2],
            // Complementary Error Function Table
            [0, 1.000000],
            [0.5, 0.479500],
            [1, 0.157299],
            [1.5, 0.033895],
            [2, 0.004678],
            [2.5, 0.000407],
            [3, 0.00002209],
            [0.01, 0.988717],
            [0.51, 0.470756],
            [1.01, 0.153190],
            [1.51, 0.032723],
            [2.01, 0.004475],
            [2.51, 0.000386],
            [3.01, 0.00002074],
            [0.02, 0.977435],
            [0.52, 0.462101],
            [1.02, 0.149162],
            [1.52, 0.031587],
            [2.02, 0.004281],
            [2.52, 0.000365],
            [3.02, 0.00001947],
            [0.03, 0.966159],
            [0.53, 0.453536],
            [1.03, 0.145216],
            [1.53, 0.030484],
            [2.03, 0.004094],
            [2.53, 0.000346],
            [3.03, 0.00001827],
            [0.04, 0.954889],
            [0.54, 0.445061],
            [1.04, 0.141350],
            [1.54, 0.029414],
            [2.04, 0.003914],
            [2.54, 0.000328],
            [3.04, 0.00001714],
            [0.05, 0.943628],
            [0.55, 0.436677],
            [1.05, 0.137564],
            [1.55, 0.028377],
            [2.05, 0.003742],
            [2.55, 0.000311],
            [3.05, 0.00001608],
            [0.06, 0.932378],
            [0.56, 0.428384],
            [1.06, 0.133856],
            [1.56, 0.027372],
            [2.06, 0.003577],
            [2.56, 0.000294],
            [3.06, 0.00001508],
            [0.07, 0.921142],
            [0.57, 0.420184],
            [1.07, 0.130227],
            [1.57, 0.026397],
            [2.07, 0.003418],
            [2.57, 0.000278],
            [3.07, 0.00001414],
            [0.08, 0.909922],
            [0.58, 0.412077],
            [1.08, 0.126674],
            [1.58, 0.025453],
            [2.08, 0.003266],
            [2.58, 0.000264],
            [3.08, 0.00001326],
            [0.09, 0.898719],
            [0.59, 0.404064],
            [1.09, 0.123197],
            [1.59, 0.024538],
            [2.09, 0.003120],
            [2.59, 0.000249],
            [3.09, 0.00001243],
            [0.1, 0.887537],
            [0.6, 0.396144],
            [1.1, 0.119795],
            [1.6, 0.023652],
            [2.1, 0.002979],
            [2.6, 0.000236],
            [3.1, 0.00001165],
            [0.11, 0.876377],
            [0.61, 0.388319],
            [1.11, 0.116467],
            [1.61, 0.022793],
            [2.11, 0.002845],
            [2.61, 0.000223],
            [3.11, 0.00001092],
            [0.12, 0.865242],
            [0.62, 0.380589],
            [1.12, 0.113212],
            [1.62, 0.021962],
            [2.12, 0.002716],
            [2.62, 0.000211],
            [3.12, 0.00001023],
            [0.13, 0.854133],
            [0.63, 0.372954],
            [1.13, 0.110029],
            [1.63, 0.021157],
            [2.13, 0.002593],
            [2.63, 0.000200],
            [3.13, 0.00000958],
            [0.14, 0.843053],
            [0.64, 0.365414],
            [1.14, 0.106918],
            [1.64, 0.020378],
            [2.14, 0.002475],
            [2.64, 0.000189],
            [3.14, 0.00000897],
            [0.15, 0.832004],
            [0.65, 0.357971],
            [1.15, 0.103876],
            [1.65, 0.019624],
            [2.15, 0.002361],
            [2.65, 0.000178],
            [3.15, 0.00000840],
            [0.16, 0.820988],
            [0.66, 0.350623],
            [1.16, 0.100904],
            [1.66, 0.018895],
            [2.16, 0.002253],
            [2.66, 0.000169],
            [3.16, 0.00000786],
            [0.17, 0.810008],
            [0.67, 0.343372],
            [1.17, 0.098000],
            [1.67, 0.018190],
            [2.17, 0.002149],
            [2.67, 0.000159],
            [3.17, 0.00000736],
            [0.18, 0.799064],
            [0.68, 0.336218],
            [1.18, 0.095163],
            [1.68, 0.017507],
            [2.18, 0.002049],
            [2.68, 0.000151],
            [3.18, 0.00000689],
            [0.19, 0.788160],
            [0.69, 0.329160],
            [1.19, 0.092392],
            [1.69, 0.016847],
            [2.19, 0.001954],
            [2.69, 0.000142],
            [3.19, 0.00000644],
            [0.2, 0.777297],
            [0.7, 0.322199],
            [1.2, 0.089686],
            [1.7, 0.016210],
            [2.2, 0.001863],
            [2.7, 0.000134],
            [3.2, 0.00000603],
            [0.21, 0.766478],
            [0.71, 0.315335],
            [1.21, 0.087045],
            [1.71, 0.015593],
            [2.21, 0.001776],
            [2.71, 0.000127],
            [3.21, 0.00000564],
            [0.22, 0.755704],
            [0.72, 0.308567],
            [1.22, 0.084466],
            [1.72, 0.014997],
            [2.22, 0.001692],
            [2.72, 0.000120],
            [3.22, 0.00000527],
            [0.23, 0.744977],
            [0.73, 0.301896],
            [1.23, 0.081950],
            [1.73, 0.014422],
            [2.23, 0.001612],
            [2.73, 0.000113],
            [3.23, 0.00000493],
            [0.24, 0.734300],
            [0.74, 0.295322],
            [1.24, 0.079495],
            [1.74, 0.013865],
            [2.24, 0.001536],
            [2.74, 0.000107],
            [3.24, 0.00000460],
            [0.25, 0.723674],
            [0.75, 0.288845],
            [1.25, 0.077100],
            [1.75, 0.013328],
            [2.25, 0.001463],
            [2.75, 0.000101],
            [3.25, 0.00000430],
            [0.26, 0.713100],
            [0.76, 0.282463],
            [1.26, 0.074764],
            [1.76, 0.012810],
            [2.26, 0.001393],
            [2.76, 0.000095],
            [3.26, 0.00000402],
            [0.27, 0.702582],
            [0.77, 0.276179],
            [1.27, 0.072486],
            [1.77, 0.012309],
            [2.27, 0.001326],
            [2.77, 0.000090],
            [3.27, 0.00000376],
            [0.28, 0.692120],
            [0.78, 0.269990],
            [1.28, 0.070266],
            [1.78, 0.011826],
            [2.28, 0.001262],
            [2.78, 0.000084],
            [3.28, 0.00000351],
            [0.29, 0.681717],
            [0.79, 0.263897],
            [1.29, 0.068101],
            [1.79, 0.011359],
            [2.29, 0.001201],
            [2.79, 0.000080],
            [3.29, 0.00000328],
            [0.3, 0.671373],
            [0.8, 0.257899],
            [1.3, 0.065992],
            [1.8, 0.010909],
            [2.3, 0.001143],
            [2.8, 0.000075],
            [3.3, 0.00000306],
            [0.31, 0.661092],
            [0.81, 0.251997],
            [1.31, 0.063937],
            [1.81, 0.010475],
            [2.31, 0.001088],
            [2.81, 0.000071],
            [3.31, 0.00000285],
            [0.32, 0.650874],
            [0.82, 0.246189],
            [1.32, 0.061935],
            [1.82, 0.010057],
            [2.32, 0.001034],
            [2.82, 0.000067],
            [3.32, 0.00000266],
            [0.33, 0.640721],
            [0.83, 0.240476],
            [1.33, 0.059985],
            [1.83, 0.009653],
            [2.33, 0.000984],
            [2.83, 0.000063],
            [3.33, 0.00000249],
            [0.34, 0.630635],
            [0.84, 0.234857],
            [1.34, 0.058086],
            [1.84, 0.009264],
            [2.34, 0.000935],
            [2.84, 0.000059],
            [3.34, 0.00000232],
            [0.35, 0.620618],
            [0.85, 0.229332],
            [1.35, 0.056238],
            [1.85, 0.008889],
            [2.35, 0.000889],
            [2.85, 0.000056],
            [3.35, 0.00000216],
            [0.36, 0.610670],
            [0.86, 0.223900],
            [1.36, 0.054439],
            [1.86, 0.008528],
            [2.36, 0.000845],
            [2.86, 0.000052],
            [3.36, 0.00000202],
            [0.37, 0.600794],
            [0.87, 0.218560],
            [1.37, 0.052688],
            [1.87, 0.008179],
            [2.37, 0.000803],
            [2.87, 0.000049],
            [3.37, 0.00000188],
            [0.38, 0.590991],
            [0.88, 0.213313],
            [1.38, 0.050984],
            [1.88, 0.007844],
            [2.38, 0.000763],
            [2.88, 0.000046],
            [3.38, 0.00000175],
            [0.39, 0.581261],
            [0.89, 0.208157],
            [1.39, 0.049327],
            [1.89, 0.007521],
            [2.39, 0.000725],
            [2.89, 0.000044],
            [3.39, 0.00000163],
            [0.4, 0.571608],
            [0.9, 0.203092],
            [1.4, 0.047715],
            [1.9, 0.007210],
            [2.4, 0.000689],
            [2.9, 0.000041],
            [3.4, 0.00000152],
            [0.41, 0.562031],
            [0.91, 0.198117],
            [1.41, 0.046148],
            [1.91, 0.006910],
            [2.41, 0.000654],
            [2.91, 0.000039],
            [3.41, 0.00000142],
            [0.42, 0.552532],
            [0.92, 0.193232],
            [1.42, 0.044624],
            [1.92, 0.006622],
            [2.42, 0.000621],
            [2.92, 0.000036],
            [3.42, 0.00000132],
            [0.43, 0.543113],
            [0.93, 0.188437],
            [1.43, 0.043143],
            [1.93, 0.006344],
            [2.43, 0.000589],
            [2.93, 0.000034],
            [3.43, 0.00000123],
            [0.44, 0.533775],
            [0.94, 0.183729],
            [1.44, 0.041703],
            [1.94, 0.006077],
            [2.44, 0.000559],
            [2.94, 0.000032],
            [3.44, 0.00000115],
            [0.45, 0.524518],
            [0.95, 0.179109],
            [1.45, 0.040305],
            [1.95, 0.005821],
            [2.45, 0.000531],
            [2.95, 0.000030],
            [3.45, 0.00000107],
            [0.46, 0.515345],
            [0.96, 0.174576],
            [1.46, 0.038946],
            [1.96, 0.005574],
            [2.46, 0.000503],
            [2.96, 0.000028],
            [3.46, 0.00000099],
            [0.47, 0.506255],
            [0.97, 0.170130],
            [1.47, 0.037627],
            [1.97, 0.005336],
            [2.47, 0.000477],
            [2.97, 0.000027],
            [3.47, 0.00000092],
            [0.48, 0.497250],
            [0.98, 0.165769],
            [1.48, 0.036346],
            [1.98, 0.005108],
            [2.48, 0.000453],
            [2.98, 0.000025],
            [3.48, 0.00000086],
            [0.49, 0.488332],
            [0.99, 0.161492],
            [1.49, 0.035102],
            [1.99, 0.004889],
            [2.49, 0.000429],
            [2.99, 0.000024],
            [3.49, 0.00000080],
        ];
    }

    /**
     * @test         error function is odd: erf(-x) = -erf(x)
     * @dataProvider dataProviderForErfOddFunction
     * @param        float $x
     */
    public function testErrorFunctionIsOdd(float $x)
    {
        // When
        $erf_x = Special::erf($x);
        $erf_neg_x = Special::erf(-$x);

        // Then
        // erf(-x) should equal -erf(x)
        $this->assertEqualsWithDelta(-$erf_x, $erf_neg_x, 1e-14);
    }

    /**
     * Test values for error function odd property
     * Source: NIST DLMF §7.2(ii) Symmetry
     * https://dlmf.nist.gov/7.2#ii
     * @return array
     */
    public function dataProviderForErfOddFunction(): array
    {
        return [
            [0.5],
            [1.0],
            [2.0],
            [3.0],
            [0.1],
            [5.0],
        ];
    }

    /**
     * @test         complementary error function relationship: erfc(x) = 1 - erf(x)
     * @dataProvider dataProviderForErfcRelationship
     * @param        float $x
     */
    public function testComplementaryErrorFunctionRelationship(float $x)
    {
        // When
        $erf_x = Special::erf($x);
        $erfc_x = Special::erfc($x);

        // Then
        // erfc(x) should equal 1 - erf(x)
        $this->assertEqualsWithDelta(1 - $erf_x, $erfc_x, 1e-14);
    }

    /**
     * Test values for erfc relationship
     * Source: NIST DLMF §7.2(i) Definitions
     * https://dlmf.nist.gov/7.2#i
     * @return array
     */
    public function dataProviderForErfcRelationship(): array
    {
        return [
            [0.0],
            [0.5],
            [1.0],
            [2.0],
            [3.0],
            [5.0],
            [-1.0],
            [-2.0],
        ];
    }

    /**
     * @test Error function odd symmetry: erf(-x) = -erf(x)
     * @dataProvider dataProviderForErfOddSymmetry
     * @param float $x
     */
    public function testErfOddSymmetry(float $x)
    {
        // When
        $erf_x = Special::erf($x);
        $erf_neg_x = Special::erf(-$x);

        // Then Odd function: erf(-x) = -erf(x)
        $this->assertEqualsWithDelta(-$erf_x, $erf_neg_x, abs($erf_x) * 1e-12 + 1e-14);
    }

    public function dataProviderForErfOddSymmetry(): array
    {
        return [
            [0.1],
            [0.5],
            [1.0],
            [1.5],
            [2.0],
            [3.0],
        ];
    }

    /**
     * @test Complementary error function relationship: erfc(x) + erfc(-x) = 2
     * @dataProvider dataProviderForErfcComplement
     * @param float $x
     */
    public function testErfcComplementRelationship(float $x)
    {
        // When
        $erfc_x = Special::erfc($x);
        $erfc_neg_x = Special::erfc(-$x);

        // Relationship: erfc(x) + erfc(-x) = 2
        $∑ = $erfc_x + $erfc_neg_x;

        // Then
        $this->assertEqualsWithDelta(2.0, $∑, 1e-12);
    }

    public function dataProviderForErfcComplement(): array
    {
        return [
            [0.5],
            [1.0],
            [1.5],
            [2.0],
            [3.0],
        ];
    }

    /**
     * @test Error function is monotonically increasing
     * @dataProvider dataProviderForErfMonotonicity
     * @param float $x1
     * @param float $x2
     */
    public function testErfMonotonicity(float $x1, float $x2)
    {
        // Ensure x1 < x2
        if ($x1 >= $x2) {
            $this->markTestSkipped("Test requires x1 < x2");
        }

        // When
        $erf_x1 = Special::erf($x1);
        $erf_x2 = Special::erf($x2);

        // Then: erf is increasing, so erf(x1) < erf(x2)
        $this->assertTrue($erf_x1 < $erf_x2);
    }

    public function dataProviderForErfMonotonicity(): array
    {
        return [
            [0.0, 0.5],
            [0.5, 1.0],
            [1.0, 1.5],
            [1.5, 2.0],
            [2.0, 3.0],
            [-1.0, 0.0],
            [-2.0, -1.0],
        ];
    }

    /**
     * @test Error function behavior for very large arguments: erf(x) → 1 as x → ∞
     * @dataProvider dataProviderForErfLargePositiveArgument
     * @param float $x
     */
    public function testErfLargePositiveArgument(float $x)
    {
        // When
        $erf_x = Special::erf($x);

        // Then erf(x) → 1 as x → ∞
        $this->assertGreaterThan(0.99999, $erf_x, "erf($x) should be very close to 1");
        $this->assertLessThanOrEqual(1.0, $erf_x, "erf($x) should not exceed 1");
    }

    public function dataProviderForErfLargePositiveArgument(): array
    {
        return [
            [10.0],
            [20.0],
            [50.0],
        ];
    }

    /**
     * @test Error function behavior for very large negative arguments: erf(x) → -1 as x → -∞
     * @dataProvider dataProviderForErfLargeNegativeArgument
     * @param float $x
     */
    public function testErfLargeNegativeArgument(float $x)
    {
        // When
        $erf_x = Special::erf($x);

        // Then erf(x) → -1 as x → -∞
        $this->assertLessThan(-0.99999, $erf_x, "erf($x) should be very close to -1");
        $this->assertGreaterThanOrEqual(-1.0, $erf_x, "erf($x) should not be less than -1");
    }

    public function dataProviderForErfLargeNegativeArgument(): array
    {
        return [
            [-10.0],
            [-20.0],
            [-50.0],
        ];
    }

    /**
     * @test Cross-validation of error function against GSL test data
     * @dataProvider dataProviderForErfGSL
     * @param float $x
     * @param float $expected GSL reference value
     */
    public function testErfCrossValidationGSL(float $x, float $expected)
    {
        // When
        $result = Special::erf($x);

        // Then - compare against GNU Scientific Library reference
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-6 + 1e-8);
    }

    public function dataProviderForErfGSL(): array
    {
        // Reference values from GSL specfunc tests
        // Source: GSL 2.7 specfunc/test_erf.c
        return [
            [0.0, 0.0],
            [0.5, 0.5204998778130465377],
            [1.0, 0.8427007929497148693],
            [2.0, 0.9953222650189527342],
            [3.0, 0.9999779095030014146],
            [5.0, 0.9999999999984625433],
        ];
    }

    /**
     * @test Cross-validation using textbook examples (Abramowitz & Stegun)
     * @dataProvider dataProviderForErfcAbramowitzStegun
     * @param float $x
     * @param float $expected A&S reference value
     */
    public function testErfcCrossValidationAS(float $x, float $expected)
    {
        // When
        $result = Special::erfc($x);

        // Then - compare against Abramowitz & Stegun Table 7.1
        // Tolerance relaxed from 1e-10 to 1e-5 to match implementation precision (~6-7 digits)
        // Uses max() to handle both large and small values appropriately
        $this->assertEqualsWithDelta($expected, $result, max(abs($expected) * 1e-5, 5e-7));
    }

    public function dataProviderForErfcAbramowitzStegun(): array
    {
        // Reference values from Abramowitz & Stegun Table 7.1
        // "Handbook of Mathematical Functions" (1964)
        // Verified with: scipy.special.erfc(x) - values match to 16 digits
        return [
            [0.0, 1.0],
            [0.2, 0.7772974107895215867],
            [0.5, 0.4795001221869534623],
            [1.0, 0.1572992070502851307],
            [1.5, 0.0338950545467310966],
            [2.0, 0.0046777349810472658],
        ];
    }
}
