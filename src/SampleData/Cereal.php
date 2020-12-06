<?php

namespace MathPHP\SampleData;

/**
 * cereal dataset (Data from cereals - R chemometrics)
 * https://rdrr.io/cran/chemometrics/man/cereal.html
 *
 * For 15 cereals an X and Y data set, measured on the same objects, is available.
 * The X data are 145 infrared spectra, and the Y data are 6 chemical/technical properties (Heating value, C, H, N, Starch, Ash).
 * Also the scaled Y data are included (mean 0, variance 1 for each column).
 * The cereals come from 5 groups B=Barley, M=Maize, R=Rye, T=Triticale, W=Wheat.
 *
 * X:   15 rows and 145 columns
 * Y:   15 rows and 6 columns
 * Ysc: 15 rows and 6 columns
 *
 * Source: K. Varmuza and P. Filzmoser: Introduction to Multivariate Statistical Analysis in Chemometrics. CRC Press, Boca Raton, FL, 2009.
 */
class Cereal
{
    const CEREALS = ['B1', 'B2', 'B3', 'M1', 'M2', 'M3', 'R1', 'R2', 'R3', 'T1', 'T2', 'T3', 'W1', 'W2', 'W3'];

    const X_LABELS = ['X1126.0', 'X1134.0', 'X1142.0', 'X1150.0', 'X1158.0', 'X1166.0', 'X1174.0', 'X1182.0', 'X1190.0', 'X1198.0', 'X1206.0', 'X1214.0', 'X1222.0', 'X1230.0', 'X1238.0', 'X1246.0', 'X1254.0', 'X1262.0', 'X1270.0', 'X1278.0', 'X1286.0', 'X1294.0', 'X1302.0', 'X1310.0', 'X1318.0', 'X1326.0', 'X1334.0', 'X1342.0', 'X1350.0', 'X1358.0', 'X1366.0', 'X1374.0', 'X1382.0', 'X1390.0', 'X1398.0', 'X1406.0', 'X1414.0', 'X1422.0', 'X1430.0', 'X1438.0', 'X1446.0', 'X1454.0', 'X1462.0', 'X1470.0', 'X1478.0', 'X1486.0', 'X1494.0', 'X1502.0', 'X1510.0', 'X1518.0', 'X1526.0', 'X1534.0', 'X1542.0', 'X1550.0', 'X1558.0', 'X1566.0', 'X1574.0', 'X1582.0', 'X1590.0', 'X1598.0', 'X1606.0', 'X1614.0', 'X1622.0', 'X1630.0', 'X1638.0', 'X1646.0', 'X1654.0', 'X1662.0', 'X1670.0', 'X1678.0', 'X1686.0', 'X1694.0', 'X1702.0', 'X1710.0', 'X1718.0', 'X1726.0', 'X1734.0', 'X1742.0', 'X1750.0', 'X1758.0', 'X1766.0', 'X1774.0', 'X1782.0', 'X1790.0', 'X1798.0', 'X1806.0', 'X1814.0', 'X1822.0', 'X1830.0', 'X1838.0', 'X1846.0', 'X1854.0', 'X1862.0', 'X1870.0', 'X1878.0', 'X1886.0', 'X1894.0', 'X1902.0', 'X1910.0', 'X1918.0', 'X1926.0', 'X1934.0', 'X1942.0', 'X1950.0', 'X1958.0', 'X1966.0', 'X1974.0', 'X1982.0', 'X1990.0', 'X1998.0', 'X2006.0', 'X2014.0', 'X2022.0', 'X2030.0', 'X2038.0', 'X2046.0', 'X2054.0', 'X2062.0', 'X2070.0', 'X2078.0', 'X2086.0', 'X2094.0', 'X2102.0', 'X2110.0', 'X2118.0', 'X2126.0', 'X2134.0', 'X2142.0', 'X2150.0', 'X2158.0', 'X2166.0', 'X2174.0', 'X2182.0', 'X2190.0', 'X2198.0', 'X2206.0', 'X2214.0', 'X2222.0', 'X2230.0', 'X2238.0', 'X2246.0', 'X2254.0', 'X2262.0', 'X2270.0', 'X2278.0'];

    const X_DATA = [
        'B1' => [0.002682755, 0.003370673, 0.004085942, 0.004471942, 0.004429410, 0.003964442, 0.003122120, 0.001980487, 0.0006682644, -0.0005840643, -0.001477618, -0.001744304, -0.0014020944, -7.012358e-04, 1.044307e-05, 0.0004517011, 5.196040e-04, 2.649459e-04, -9.246170e-05, -0.0003359030, -0.0003160563, 4.084464e-05, 0.0007770419, 0.001868637, 0.003225544, 0.004780581, 0.006401237, 0.007622414, 0.008146129, 0.008325112, 0.008591176, 0.009532935, 0.011599077, 0.01479092, 0.01822566, 0.02060247, 0.02112873, 0.01946873, 0.01604855, 0.011760667, 0.007526339, 0.003968807, 0.0012693380, -0.0006789159, -0.002101858, -0.003154883, -0.003857937, -0.004178052, -0.004127765, -0.003808664, -0.003313430, -0.002761283, -0.002305585, -0.002053739, -0.002084096, -0.002437808, -0.003056512, -0.003818549, -0.004556699, -0.005152781, -0.005475702, -0.005475869, -0.005184242, -0.004614478, -0.003776590, -0.002652845, -0.001175940, 6.954346e-04, 0.002664074, 0.004228408, 0.005055542, 0.005143774, 0.004631161, 0.003815687, 0.003180051, 0.002931677, 0.002898672, 0.002820737, 0.002667333, 0.002372362, 0.001842147, 0.0011688900, 5.050689e-04, 5.247589e-05, -1.229959e-04, -3.124308e-05, 1.747809e-04, 0.0002616254, 3.666456e-04, 0.0008830640, 0.002411519, 0.005075179, 0.009416806, 0.01600487, 0.02411621, 0.03177404, 0.03688798, 0.03794569, 0.03385833, 0.02535710, 0.01489938, 0.005161122, -0.002366087, -0.007195404, -0.009651135, -0.010407705, -0.010006314, -0.008756957, -0.006703062, -0.003775413, -9.293912e-05, 0.003841576, 0.007390919, 0.009967920, 0.01128782, 0.01129210, 0.010237400, 0.008593487, 0.006742679, 0.004851171, 0.003050590, 0.001411505, -8.669715e-05, -0.0014368520, -0.002549195, -0.003275797, -0.003729765, -0.003756917, -0.003465245, -0.003198487, -0.003044090, -0.0028700801, -2.141716e-03, -0.0005941851, 0.0019175303, 0.005210814, 0.008103427, 0.009379880, 0.008545065, 0.006105137, 0.002739386, -1.161764e-03, -0.004621026, -0.007536137, -0.009485135],
        'B2' => [0.002781597, 0.003474863, 0.004191472, 0.004556836, 0.004465460, 0.003962058, 0.003099917, 0.001956450, 0.0006306790, -0.0006263133, -0.001505752, -0.001770189, -0.0014064449, -6.685687e-04, 7.084182e-05, 0.0005037493, 5.604844e-04, 3.115770e-04, -4.975330e-05, -0.0002797165, -0.0002438398, 1.185037e-04, 0.0008466617, 0.001913700, 0.003239261, 0.004759875, 0.006338496, 0.007525012, 0.008027831, 0.008192304, 0.008441036, 0.009361862, 0.011382937, 0.01447700, 0.01780021, 0.02010779, 0.02060866, 0.01896012, 0.01562258, 0.011469290, 0.007349825, 0.003866391, 0.0012305737, -0.0006857305, -0.002098934, -0.003133239, -0.003804685, -0.004082544, -0.004009488, -0.003683757, -0.003214862, -0.002692186, -0.002249990, -0.002007683, -0.002041890, -0.002378021, -0.002983521, -0.003759707, -0.004517520, -0.005119340, -0.005439926, -0.005441440, -0.005140884, -0.004568314, -0.003755827, -0.002673263, -0.001232430, 6.052252e-04, 0.002552655, 0.004111083, 0.004932234, 0.005018462, 0.004506775, 0.003687346, 0.003054565, 0.002818694, 0.002799763, 0.002760495, 0.002662475, 0.002424756, 0.001904524, 0.0012091269, 5.418535e-04, 8.165614e-05, -9.342132e-05, 1.568286e-05, 2.422662e-04, 0.0003208102, 3.973596e-04, 0.0009280735, 0.002463977, 0.005090307, 0.009384967, 0.01588091, 0.02381865, 0.03126644, 0.03624632, 0.03729672, 0.03330715, 0.02495941, 0.01471243, 0.005154292, -0.002286356, -0.007123764, -0.009558597, -0.010259492, -0.009862814, -0.008628964, -0.006568902, -0.003677792, -1.140806e-04, 0.003708162, 0.007201660, 0.009746212, 0.01101808, 0.01104756, 0.010061627, 0.008447927, 0.006608289, 0.004836597, 0.003138287, 0.001524630, 4.767942e-05, -0.0013183953, -0.002451729, -0.003206522, -0.003672445, -0.003857930, -0.003788274, -0.003505979, -0.003208247, -0.0028195234, -2.021656e-03, -0.0005066908, 0.0019790134, 0.005028758, 0.007810556, 0.009145642, 0.008475769, 0.006070795, 0.002501408, -1.408486e-03, -0.004848684, -0.007720306, -0.009636176],
        'B3' => [0.002499975, 0.003187669, 0.003895576, 0.004248752, 0.004169549, 0.003711650, 0.002902776, 0.001808968, 0.0005615940, -0.0006385698, -0.001493687, -0.001758253, -0.0014275849, -7.488642e-04, -7.201008e-05, 0.0003412947, 3.813857e-04, 1.223758e-04, -2.348095e-04, -0.0004773265, -0.0004567509, -9.906394e-05, 0.0006257485, 0.001694359, 0.003026666, 0.004562124, 0.006162752, 0.007363235, 0.007856555, 0.007981571, 0.008149470, 0.008948856, 0.010850703, 0.01391650, 0.01730893, 0.01975072, 0.02042122, 0.01892004, 0.01565154, 0.011481954, 0.007324133, 0.003812868, 0.0011784802, -0.0007086045, -0.002087514, -0.003104825, -0.003778088, -0.004084844, -0.004038694, -0.003719164, -0.003232827, -0.002683986, -0.002219447, -0.001968437, -0.002008474, -0.002357493, -0.002971587, -0.003740654, -0.004490853, -0.005075666, -0.005382772, -0.005365399, -0.005051157, -0.004464496, -0.003619516, -0.002491942, -0.001032456, 7.665738e-04, 0.002645247, 0.004114779, 0.004868983, 0.004934820, 0.004448788, 0.003659483, 0.003031230, 0.002779094, 0.002723808, 0.002664916, 0.002581351, 0.002371918, 0.001899972, 0.0012575950, 6.231712e-04, 1.829754e-04, 1.152002e-05, 9.399848e-05, 2.711419e-04, 0.0003057999, 3.282080e-04, 0.0007680127, 0.002201774, 0.004694345, 0.008791955, 0.01509660, 0.02294884, 0.03039240, 0.03539806, 0.03651871, 0.03261141, 0.02432717, 0.01410972, 0.004625464, -0.002662650, -0.007329388, -0.009626671, -0.010199789, -0.009644648, -0.008266589, -0.006086405, -0.003038131, 6.850126e-04, 0.004638533, 0.008197546, 0.010763180, 0.01202425, 0.01194515, 0.010807760, 0.009027275, 0.007005592, 0.005044249, 0.003229512, 0.001575640, 4.602052e-05, -0.0012741947, -0.002300954, -0.003020785, -0.003317502, -0.003226229, -0.002905509, -0.002548711, -0.002199996, -0.0018321890, -1.125035e-03, 0.0005619689, 0.0031902529, 0.006377113, 0.009057070, 0.010202642, 0.009419061, 0.006997782, 0.003739993, 1.517817e-04, -0.003201539, -0.006203081, -0.008338081],
        'M1' => [0.004174954, 0.005263667, 0.006169902, 0.006547854, 0.006341550, 0.005639171, 0.004525627, 0.003034692, 0.0012495504, -0.0006013924, -0.002062233, -0.002730248, -0.0025427050, -1.769690e-03, -8.415443e-04, -0.0001502366, 1.042821e-04, -4.402885e-05, -3.597525e-04, -0.0005552326, -0.0004589001, 2.584511e-05, 0.0009507134, 0.002288624, 0.003918662, 0.005729520, 0.007557320, 0.008919038, 0.009516178, 0.009726386, 0.010052575, 0.011157994, 0.013563547, 0.01725720, 0.02127475, 0.02419110, 0.02499639, 0.02317041, 0.01917826, 0.014098705, 0.009018980, 0.004705524, 0.0014778537, -0.0008084069, -0.002462608, -0.003661350, -0.004410502, -0.004693468, -0.004559369, -0.004110187, -0.003504971, -0.002871443, -0.002351272, -0.002092937, -0.002201814, -0.002700216, -0.003514887, -0.004510431, -0.005492677, -0.006268712, -0.006710116, -0.006775597, -0.006482623, -0.005862423, -0.004934987, -0.003682906, -0.001998054, 2.164847e-04, 0.002679569, 0.004756393, 0.005911093, 0.006044115, 0.005308284, 0.004116884, 0.003196171, 0.002904206, 0.002989106, 0.003077960, 0.003006291, 0.002609113, 0.001757937, 0.0006659546, -3.303184e-04, -1.001143e-03, -1.253600e-03, -1.112554e-03, -7.987317e-04, -0.0006250661, -4.661516e-04, 0.0002038063, 0.002061307, 0.005216763, 0.010231774, 0.01759953, 0.02648548, 0.03480343, 0.04037810, 0.04163807, 0.03734788, 0.02816954, 0.01670804, 0.005835930, -0.002763794, -0.008506838, -0.011473517, -0.012320387, -0.011760463, -0.010164433, -0.007592883, -0.004076198, 1.213722e-04, 0.004533394, 0.008499404, 0.011418537, 0.01297468, 0.01315393, 0.012216471, 0.010504799, 0.008360415, 0.006080137, 0.003836945, 0.001681612, -3.560285e-04, -0.0022039216, -0.003797675, -0.004985394, -0.005712625, -0.005920299, -0.005666646, -0.005145933, -0.004603392, -0.0039895899, -2.812815e-03, -0.0007010375, 0.0025321017, 0.006541874, 0.010366611, 0.012499565, 0.012029768, 0.009372733, 0.005271052, 5.548965e-04, -0.003937807, -0.007531964, -0.009831782],
        'M2' => [0.004345646, 0.005474921, 0.006391608, 0.006746469, 0.006506085, 0.005781125, 0.004634905, 0.003135009, 0.0013650168, -0.0004477912, -0.001881758, -0.002545929, -0.0023517238, -1.579458e-03, -6.627219e-04, 0.0000147440, 2.535726e-04, 9.231012e-05, -2.412234e-04, -0.0004606728, -0.0003671653, 1.287172e-04, 0.0010672200, 0.002419844, 0.004054951, 0.005850386, 0.007636835, 0.008943941, 0.009503340, 0.009694538, 0.010015699, 0.011124144, 0.013501774, 0.01710759, 0.02097158, 0.02371427, 0.02439291, 0.02252024, 0.01858219, 0.013633855, 0.008723007, 0.004562629, 0.0014276970, -0.0007930486, -0.002406846, -0.003577069, -0.004310766, -0.004570975, -0.004400023, -0.003932530, -0.003315622, -0.002689564, -0.002191274, -0.001953098, -0.002058876, -0.002530965, -0.003317842, -0.004279538, -0.005245077, -0.006027524, -0.006480321, -0.006549783, -0.006267641, -0.005673099, -0.004791047, -0.003623513, -0.002039784, 7.126115e-05, 0.002450871, 0.004464292, 0.005607285, 0.005752421, 0.005065078, 0.003955534, 0.003143830, 0.002963420, 0.003146622, 0.003314945, 0.003277811, 0.002876159, 0.002008220, 0.0009135150, -9.116438e-05, -7.646372e-04, -1.024257e-03, -8.947659e-04, -6.052140e-04, -0.0004465571, -2.920650e-04, 0.0003735353, 0.002193650, 0.005276092, 0.010144351, 0.01729369, 0.02594963, 0.03402665, 0.03939372, 0.04054068, 0.03629332, 0.02728390, 0.01608533, 0.005547786, -0.002776107, -0.008336090, -0.011230531, -0.012056280, -0.011496454, -0.009914364, -0.007387416, -0.003990938, 3.809112e-05, 0.004238693, 0.008024356, 0.010832548, 0.01236075, 0.01260419, 0.011755438, 0.010131307, 0.008109993, 0.005969267, 0.003764891, 0.001583560, -5.014751e-04, -0.0024205095, -0.004019809, -0.005198664, -0.005881262, -0.006052232, -0.005830036, -0.005478653, -0.005104375, -0.0046157502, -3.621571e-03, -0.0017017958, 0.0014552090, 0.005437855, 0.009177373, 0.011212457, 0.010695015, 0.008223997, 0.004287082, -4.291066e-04, -0.004987279, -0.008536770, -0.010693177],
        'M3' => [0.004693636, 0.005933791, 0.006922649, 0.007282881, 0.007010047, 0.006208319, 0.004987427, 0.003394474, 0.0014698871, -0.0005246264, -0.002148449, -0.002952206, -0.0028334786, -2.061814e-03, -1.087845e-03, -0.0003469929, -4.610598e-05, -1.504189e-04, -4.384004e-04, -0.0006177050, -0.0004805166, 7.146048e-05, 0.0010882221, 0.002549677, 0.004330439, 0.006279061, 0.008194065, 0.009606531, 0.010235171, 0.010474245, 0.010880373, 0.012142347, 0.014755330, 0.01864278, 0.02277021, 0.02563644, 0.02621040, 0.02406142, 0.01975206, 0.014402333, 0.009120679, 0.004681782, 0.0013591105, -0.0010147057, -0.002738714, -0.003960672, -0.004694188, -0.004951019, -0.004783748, -0.004307933, -0.003681280, -0.003030788, -0.002511692, -0.002259119, -0.002361494, -0.002851366, -0.003658979, -0.004657939, -0.005640037, -0.006437108, -0.006899891, -0.006969862, -0.006665459, -0.006021661, -0.005072898, -0.003778826, -0.002030534, 2.574079e-04, 0.002805480, 0.004984924, 0.006217424, 0.006371706, 0.005609746, 0.004344281, 0.003329205, 0.002971950, 0.003049625, 0.003132716, 0.003067396, 0.002657516, 0.001761544, 0.0006391573, -3.940333e-04, -1.086163e-03, -1.360450e-03, -1.235929e-03, -9.547460e-04, -0.0007978602, -5.784815e-04, 0.0001785433, 0.002160707, 0.005509229, 0.010769382, 0.01839216, 0.02749131, 0.03585259, 0.04125640, 0.04221035, 0.03754604, 0.02804454, 0.01642005, 0.005568745, -0.002963086, -0.008629239, -0.011535812, -0.012396035, -0.011903130, -0.010351521, -0.007840239, -0.004384072, -2.141176e-04, 0.004181603, 0.008133568, 0.011023809, 0.01254322, 0.01266676, 0.011664109, 0.009955233, 0.007897994, 0.005742744, 0.003605595, 0.001504358, -5.026216e-04, -0.0023358604, -0.003835388, -0.004929913, -0.005622595, -0.005841123, -0.005654899, -0.005258655, -0.004846227, -0.0042773234, -3.227156e-03, -0.0011836709, 0.0021058191, 0.006195694, 0.009921309, 0.011860012, 0.011240382, 0.008526726, 0.004455484, -5.348672e-05, -0.004377292, -0.008066578, -0.010384159],
        'R1' => [0.001783141, 0.002286875, 0.002862906, 0.003169318, 0.003115767, 0.002732020, 0.002079550, 0.001197151, 0.0002029308, -0.0006809172, -0.001204506, -0.001211132, -0.0007496757, -5.858113e-05, 5.419980e-04, 0.0008194863, 7.355611e-04, 3.918828e-04, -2.302058e-05, -0.0003208144, -0.0003795873, -1.423382e-04, 0.0004039379, 0.001224162, 0.002261297, 0.003483015, 0.004786584, 0.005761545, 0.006129698, 0.006176044, 0.006177529, 0.006619789, 0.007948433, 0.01028046, 0.01301736, 0.01514918, 0.01600127, 0.01512523, 0.01272887, 0.009454561, 0.006032334, 0.003045012, 0.0007524329, -0.0008823784, -0.002032729, -0.002839752, -0.003322368, -0.003454355, -0.003286250, -0.002907478, -0.002417742, -0.001929926, -0.001562325, -0.001404566, -0.001508066, -0.001871614, -0.002430480, -0.003089671, -0.003724725, -0.004215444, -0.004474027, -0.004487794, -0.004281263, -0.003873598, -0.003274272, -0.002465279, -0.001368402, 7.066274e-05, 0.001668383, 0.002988958, 0.003712401, 0.003824351, 0.003463413, 0.002896361, 0.002510617, 0.002476783, 0.002609072, 0.002671506, 0.002598460, 0.002331564, 0.001843405, 0.0012420858, 6.629964e-04, 2.820264e-04, 1.667787e-04, 3.063813e-04, 5.529146e-04, 0.0006395191, 6.365000e-04, 0.0008883435, 0.001905697, 0.003656719, 0.006621558, 0.01144529, 0.01772415, 0.02398439, 0.02862189, 0.03032204, 0.02779294, 0.02129640, 0.01278284, 0.004546686, -0.001995900, -0.006240427, -0.008290432, -0.008733798, -0.008126978, -0.006740859, -0.004642626, -0.001825217, 1.560455e-03, 0.005092047, 0.008206925, 0.010416013, 0.01146484, 0.01136890, 0.010397269, 0.008884952, 0.007106146, 0.005294408, 0.003543340, 0.001856183, 2.616606e-04, -0.0011383706, -0.002224533, -0.002949845, -0.003258664, -0.003049924, -0.002466982, -0.001803625, -0.001295964, -0.0008510689, -1.036500e-04, 0.0012697512, 0.0035802419, 0.006587424, 0.009507996, 0.011075050, 0.010575563, 0.008520212, 0.005286807, 1.479116e-03, -0.002084878, -0.005033372, -0.006971300],
        'R2' => [0.002583537, 0.003317312, 0.004048497, 0.004393620, 0.004296371, 0.003793294, 0.002923908, 0.001764336, 0.0004674267, -0.0007137732, -0.001485541, -0.001644894, -0.0012370463, -5.301073e-04, 1.325830e-04, 0.0004952169, 4.987256e-04, 2.253917e-04, -1.507237e-04, -0.0004160027, -0.0004187600, -9.209644e-05, 0.0006044547, 0.001643444, 0.002933714, 0.004397134, 0.005899400, 0.007009671, 0.007431179, 0.007487244, 0.007576102, 0.008252220, 0.009963851, 0.01277354, 0.01594347, 0.01829361, 0.01905118, 0.01780854, 0.01487707, 0.011032173, 0.007104169, 0.003721777, 0.0011340178, -0.0007324851, -0.002071783, -0.003025209, -0.003607349, -0.003811202, -0.003671630, -0.003284829, -0.002775108, -0.002257315, -0.001846069, -0.001647124, -0.001736704, -0.002117326, -0.002729979, -0.003481285, -0.004213193, -0.004784417, -0.005093711, -0.005108471, -0.004851700, -0.004356531, -0.003655952, -0.002710745, -0.001430190, 2.386443e-04, 0.002052929, 0.003504966, 0.004269165, 0.004350684, 0.003923894, 0.003286648, 0.002867165, 0.002818031, 0.002887576, 0.002864940, 0.002736325, 0.002443877, 0.001907028, 0.0012404843, 6.005550e-04, 1.289508e-04, -7.212494e-05, 2.471080e-05, 2.527411e-04, 0.0003418980, 3.828017e-04, 0.0007878174, 0.002103793, 0.004344143, 0.008003219, 0.01366359, 0.02079911, 0.02771063, 0.03260041, 0.03409903, 0.03095695, 0.02359426, 0.01414981, 0.005138285, -0.001953273, -0.006569540, -0.008862438, -0.009440638, -0.008907514, -0.007608618, -0.005543577, -0.002643222, 9.060436e-04, 0.004652616, 0.008027875, 0.010468058, 0.01164153, 0.01156885, 0.010552835, 0.008953744, 0.007113488, 0.005256951, 0.003476218, 0.001780361, 2.016243e-04, -0.0011801083, -0.002283122, -0.003050653, -0.003389390, -0.003266867, -0.002831719, -0.002291509, -0.001784482, -0.0013153993, -6.271093e-04, 0.0008650356, 0.0033002181, 0.006260127, 0.009124636, 0.010692426, 0.010117050, 0.007786899, 0.004449275, 6.461283e-04, -0.002962092, -0.005819620, -0.007617886],
        'R3' => [0.001611596, 0.002112018, 0.002726398, 0.003101030, 0.003117942, 0.002789069, 0.002140051, 0.001245511, 0.0002386093, -0.0006728764, -0.001225982, -0.001260480, -0.0008193409, -1.534663e-04, 4.329894e-04, 0.0007089371, 6.309904e-04, 2.970152e-04, -1.065194e-04, -0.0004002798, -0.0004641744, -2.348655e-04, 0.0003034502, 0.001141070, 0.002209938, 0.003473186, 0.004825187, 0.005841342, 0.006234100, 0.006274054, 0.006270940, 0.006717749, 0.008073999, 0.01050530, 0.01341023, 0.01573711, 0.01674205, 0.01591294, 0.01344001, 0.009992178, 0.006362089, 0.003182299, 0.0007562240, -0.0009502836, -0.002145977, -0.002978207, -0.003463736, -0.003595950, -0.003426428, -0.003037231, -0.002525833, -0.002013048, -0.001626725, -0.001449883, -0.001544307, -0.001922712, -0.002516221, -0.003214870, -0.003884844, -0.004408406, -0.004681917, -0.004689571, -0.004458286, -0.004015185, -0.003378458, -0.002541406, -0.001387256, 1.343770e-04, 0.001792008, 0.003145080, 0.003888091, 0.004000407, 0.003615132, 0.003025644, 0.002627534, 0.002579189, 0.002673805, 0.002703835, 0.002622445, 0.002378023, 0.001892736, 0.0012650047, 6.618276e-04, 2.383659e-04, 8.780773e-05, 2.099653e-04, 4.408604e-04, 0.0005103397, 4.947904e-04, 0.0007601476, 0.001788675, 0.003582011, 0.006615564, 0.01150367, 0.01786383, 0.02424862, 0.02900971, 0.03076661, 0.02821556, 0.02162297, 0.01295212, 0.004574556, -0.002031073, -0.006254109, -0.008255374, -0.008641450, -0.007982811, -0.006571010, -0.004405699, -0.001461963, 2.088398e-03, 0.005768733, 0.008978671, 0.011225047, 0.01223875, 0.01206332, 0.010963775, 0.009307533, 0.007418391, 0.005448140, 0.003550035, 0.001747464, 1.051717e-04, -0.0012935897, -0.002341191, -0.002952614, -0.003151390, -0.002916215, -0.002416518, -0.001913703, -0.001419880, -0.0008690269, -9.288292e-05, 0.0013796680, 0.0038134758, 0.006874200, 0.009673741, 0.011188569, 0.010789509, 0.008698520, 0.005441437, 1.691061e-03, -0.001917970, -0.005010375, -0.007074165],
        'T1' => [0.003603812, 0.004559525, 0.005422657, 0.005798172, 0.005630409, 0.005003491, 0.003945862, 0.002541858, 0.0009339036, -0.0005772146, -0.001663107, -0.002037257, -0.0016806111, -9.125977e-04, -1.195643e-04, 0.0003787183, 4.918894e-04, 2.610709e-04, -8.702020e-05, -0.0002983070, -0.0002276935, 2.182434e-04, 0.0010856247, 0.002338560, 0.003850727, 0.005524572, 0.007210708, 0.008440215, 0.008940709, 0.009112571, 0.009451702, 0.010602373, 0.012999387, 0.01657657, 0.02038338, 0.02303610, 0.02363957, 0.02180715, 0.01804140, 0.013326918, 0.008620515, 0.004604244, 0.0015406100, -0.0006938691, -0.002336491, -0.003521238, -0.004251430, -0.004523043, -0.004404480, -0.004002347, -0.003434654, -0.002832254, -0.002343335, -0.002102780, -0.002182200, -0.002604870, -0.003324312, -0.004205443, -0.005063084, -0.005750701, -0.006149235, -0.006209269, -0.005939301, -0.005377853, -0.004557901, -0.003449457, -0.001921504, 1.093268e-04, 0.002323159, 0.004125184, 0.005088825, 0.005184623, 0.004618799, 0.003814937, 0.003319156, 0.003292724, 0.003443314, 0.003458243, 0.003285628, 0.002846937, 0.002089920, 0.0011602177, 2.531839e-04, -4.028642e-04, -6.999922e-04, -5.967277e-04, -3.125892e-04, -0.0001555475, 3.418691e-05, 0.0007206302, 0.002556306, 0.005652165, 0.010570474, 0.01785636, 0.02665671, 0.03490035, 0.04041953, 0.04164581, 0.03735116, 0.02821262, 0.01685784, 0.006112520, -0.002363170, -0.007995911, -0.010940331, -0.011867999, -0.011466437, -0.010091079, -0.007795797, -0.004530622, -5.253035e-04, 0.003720263, 0.007570966, 0.010400051, 0.01185192, 0.01193302, 0.010953247, 0.009309701, 0.007367920, 0.005352648, 0.003326215, 0.001360282, -4.894902e-04, -0.0021726051, -0.003578158, -0.004551839, -0.005059579, -0.005259227, -0.005166872, -0.004822511, -0.004562056, -0.0042757723, -3.374397e-03, -0.0015015763, 0.0014890538, 0.005169839, 0.008568323, 0.010184753, 0.009336543, 0.006711124, 0.002942456, -1.202665e-03, -0.004898393, -0.007876999, -0.009771970],
        'T2' => [0.004013149, 0.005047036, 0.005945016, 0.006339320, 0.006148504, 0.005454069, 0.004315885, 0.002790461, 0.0010406504, -0.0006439523, -0.001869604, -0.002346576, -0.0020529774, -1.287247e-03, -4.603657e-04, 0.0001055218, 2.646559e-04, 7.934740e-05, -2.299113e-04, -0.0004114585, -0.0002997600, 1.964495e-04, 0.0011345551, 0.002489984, 0.004136687, 0.005955597, 0.007768570, 0.009094285, 0.009654762, 0.009860887, 0.010270333, 0.011578707, 0.014256031, 0.01821146, 0.02240756, 0.02533534, 0.02599614, 0.02398441, 0.01983534, 0.014639170, 0.009463063, 0.005064169, 0.0017133587, -0.0007225624, -0.002478222, -0.003729281, -0.004507810, -0.004819883, -0.004725199, -0.004327145, -0.003763503, -0.003141817, -0.002614081, -0.002338343, -0.002404149, -0.002859305, -0.003637128, -0.004600429, -0.005555053, -0.006305773, -0.006709376, -0.006725184, -0.006403415, -0.005774238, -0.004859419, -0.003629304, -0.001920840, 3.412974e-04, 0.002791275, 0.004736574, 0.005731109, 0.005803654, 0.005165121, 0.004242694, 0.003629762, 0.003520278, 0.003590135, 0.003517575, 0.003304864, 0.002840004, 0.002039242, 0.0010506276, 7.502514e-05, -6.309658e-04, -9.555844e-04, -8.953417e-04, -6.566597e-04, -0.0004851702, -2.403196e-04, 0.0005442766, 0.002573442, 0.006035746, 0.011465820, 0.01937727, 0.02889285, 0.03771573, 0.04350821, 0.04459733, 0.03979974, 0.02991815, 0.01777227, 0.006413914, -0.002522013, -0.008422706, -0.011449207, -0.012417693, -0.012070704, -0.010713344, -0.008354706, -0.004989056, -8.059370e-04, 0.003715347, 0.007800164, 0.010768347, 0.01227204, 0.01230555, 0.011234620, 0.009490864, 0.007438157, 0.005347318, 0.003311505, 0.001332074, -5.899643e-04, -0.0023228023, -0.003705298, -0.004685350, -0.005228111, -0.005388287, -0.005275187, -0.005065320, -0.004885285, -0.0044619665, -3.471800e-03, -0.0014616144, 0.0016226174, 0.005243225, 0.008444346, 0.010052911, 0.009383475, 0.006837912, 0.003294000, -8.649600e-04, -0.005068060, -0.008850669, -0.011276547],
        'T3' => [0.002460872, 0.003207080, 0.003956599, 0.004331280, 0.004264773, 0.003795101, 0.002973083, 0.001847874, 0.0005640944, -0.0006517087, -0.001506184, -0.001756068, -0.0014065336, -7.207213e-04, -5.271523e-05, 0.0003328374, 3.627935e-04, 9.285313e-05, -2.854411e-04, -0.0005536096, -0.0005658399, -2.401668e-04, 0.0004410829, 0.001459005, 0.002743950, 0.004218033, 0.005746555, 0.006890851, 0.007343482, 0.007403091, 0.007435628, 0.008013032, 0.009632465, 0.01242850, 0.01569305, 0.01820751, 0.01914007, 0.01799977, 0.01505650, 0.011100721, 0.007035807, 0.003548279, 0.0009120723, -0.0009475123, -0.002254731, -0.003168990, -0.003724338, -0.003907969, -0.003761811, -0.003369197, -0.002839704, -0.002292061, -0.001859163, -0.001649157, -0.001737147, -0.002134208, -0.002779587, -0.003562339, -0.004314696, -0.004902581, -0.005218106, -0.005232685, -0.004978910, -0.004500579, -0.003803933, -0.002848549, -0.001524872, 2.223768e-04, 0.002151601, 0.003737289, 0.004589364, 0.004689463, 0.004204928, 0.003450490, 0.002911148, 0.002773504, 0.002811483, 0.002777039, 0.002635438, 0.002325435, 0.001757237, 0.0010591353, 3.939613e-04, -8.601836e-05, -2.902047e-04, -1.900359e-04, 4.225243e-05, 0.0001173415, 1.424872e-04, 0.0005229671, 0.001773638, 0.003913567, 0.007459179, 0.01302369, 0.02010402, 0.02702884, 0.03197814, 0.03354996, 0.03049124, 0.02318971, 0.01380394, 0.004879307, -0.002107729, -0.006636730, -0.008850627, -0.009323498, -0.008686404, -0.007239446, -0.004992852, -0.001887464, 1.864877e-03, 0.005761152, 0.009176981, 0.011551994, 0.01260715, 0.01237914, 0.011199080, 0.009455397, 0.007478754, 0.005455608, 0.003493363, 0.001626012, -6.393697e-05, -0.0014580673, -0.002517411, -0.003195251, -0.003457382, -0.003351640, -0.002974402, -0.002474433, -0.001888818, -0.0013525614, -5.571853e-04, 0.0010375765, 0.0035850317, 0.006725598, 0.009831266, 0.011724016, 0.011420466, 0.009191004, 0.005614656, 1.375682e-03, -0.002525127, -0.005672788, -0.007765179],
        'W1' => [0.001717432, 0.002169579, 0.002760141, 0.003114990, 0.003136779, 0.002821055, 0.002174799, 0.001263949, 0.0002300632, -0.0006666576, -0.001200155, -0.001191709, -0.0006980753, 1.321670e-05, 6.301142e-04, 0.0009280764, 8.724552e-04, 5.359476e-04, 1.086650e-04, -0.0002057490, -0.0002895054, -8.477625e-05, 0.0004338520, 0.001246782, 0.002292006, 0.003534548, 0.004886340, 0.005914474, 0.006325348, 0.006404730, 0.006447463, 0.006921775, 0.008266321, 0.01064125, 0.01342886, 0.01558168, 0.01640429, 0.01546531, 0.01298542, 0.009615467, 0.006130904, 0.003144116, 0.0009220300, -0.0006334980, -0.001739092, -0.002537233, -0.003070770, -0.003318295, -0.003291540, -0.003036754, -0.002640668, -0.002199845, -0.001825660, -0.001630657, -0.001689303, -0.002006690, -0.002534381, -0.003187244, -0.003818113, -0.004299109, -0.004558247, -0.004562361, -0.004327518, -0.003884784, -0.003224170, -0.002300942, -0.001031211, 5.931534e-04, 0.002330853, 0.003695335, 0.004369125, 0.004399839, 0.003956053, 0.003302856, 0.002825813, 0.002671264, 0.002621338, 0.002499524, 0.002341513, 0.002102224, 0.001693931, 0.0011838443, 7.067479e-04, 3.652846e-04, 2.424544e-04, 3.765063e-04, 5.976001e-04, 0.0006395357, 6.163730e-04, 0.0009065183, 0.001970275, 0.003784561, 0.006868368, 0.01182748, 0.01819969, 0.02451171, 0.02913870, 0.03074419, 0.02808855, 0.02152849, 0.01303655, 0.004913570, -0.001412390, -0.005408892, -0.007327176, -0.007779937, -0.007321449, -0.006215621, -0.004420632, -0.001784655, 1.575882e-03, 0.005173931, 0.008350606, 0.010495128, 0.01133790, 0.01093846, 0.009690732, 0.008007833, 0.006209271, 0.004517230, 0.002966581, 0.001529417, 2.449571e-04, -0.0007810997, -0.001505258, -0.001905796, -0.002034040, -0.001962519, -0.001795718, -0.001572792, -0.001510803, -0.0014862834, -1.067075e-03, 0.0001065742, 0.0023057291, 0.005219306, 0.008241126, 0.009894477, 0.009432372, 0.007249564, 0.003923875, 9.340928e-05, -0.003498654, -0.006512864, -0.008428086],
        'W2' => [0.002910101, 0.003765754, 0.004590429, 0.005007052, 0.004933619, 0.004404854, 0.003457709, 0.002171872, 0.0006749056, -0.0007634192, -0.001798629, -0.002157378, -0.0018317646, -1.106339e-03, -3.612560e-04, 0.0001108702, 2.082437e-04, -1.382989e-05, -3.602481e-04, -0.0005889814, -0.0005467141, -1.613799e-04, 0.0006177447, 0.001771912, 0.003199199, 0.004821552, 0.006487750, 0.007725410, 0.008235745, 0.008367676, 0.008539881, 0.009358306, 0.011321913, 0.01449225, 0.01803557, 0.02064818, 0.02146724, 0.02000146, 0.01662034, 0.012220410, 0.007763560, 0.003957711, 0.0010824918, -0.0009465275, -0.002399914, -0.003436722, -0.004080112, -0.004329380, -0.004233496, -0.003864813, -0.003338416, -0.002779970, -0.002325972, -0.002100526, -0.002188578, -0.002601000, -0.003258373, -0.004063535, -0.004843251, -0.005449512, -0.005780768, -0.005819514, -0.005580003, -0.005060744, -0.004289446, -0.003218530, -0.001708812, 2.765448e-04, 0.002418145, 0.004127138, 0.005024972, 0.005110602, 0.004562949, 0.003742794, 0.003134777, 0.002942527, 0.002924440, 0.002814289, 0.002601198, 0.002237922, 0.001633911, 0.0008685826, 1.207785e-04, -4.088690e-04, -6.572495e-04, -5.995101e-04, -3.844582e-04, -0.0002725920, -1.479853e-04, 0.0004025775, 0.001959119, 0.004551679, 0.008727112, 0.01510591, 0.02304435, 0.03068287, 0.03601613, 0.03751794, 0.03392690, 0.02574674, 0.01538315, 0.005568735, -0.002113462, -0.007089388, -0.009575091, -0.010271179, -0.009826389, -0.008552663, -0.006388097, -0.003249606, 6.933115e-04, 0.004928462, 0.008685724, 0.011304282, 0.01245361, 0.01219970, 0.010888290, 0.009008466, 0.006945725, 0.004938757, 0.003057017, 0.001325560, -2.249236e-04, -0.0015526490, -0.002502262, -0.003055483, -0.003268907, -0.003158439, -0.002843003, -0.002530362, -0.002305563, -0.0020279142, -1.317587e-03, 0.0002136760, 0.0029601455, 0.006620566, 0.010091604, 0.012043796, 0.011804862, 0.009626346, 0.005811495, 1.247709e-03, -0.002857000, -0.006131076, -0.008395888],
        'W3' => [0.004064321, 0.005122791, 0.006025832, 0.006421921, 0.006247005, 0.005563958, 0.004442874, 0.002945798, 0.0011870126, -0.0005461558, -0.001838730, -0.002355097, -0.0020846119, -1.317669e-03, -4.526930e-04, 0.0001299498, 3.022780e-04, 1.237323e-04, -1.924863e-04, -0.0003965391, -0.0003084205, 1.961525e-04, 0.0011360438, 0.002482396, 0.004122898, 0.005948938, 0.007781910, 0.009140867, 0.009732463, 0.009962874, 0.010361183, 0.011618882, 0.014236707, 0.01816809, 0.02237102, 0.02531167, 0.02601899, 0.02405539, 0.01992453, 0.014709161, 0.009516602, 0.005104199, 0.0017372726, -0.0007186033, -0.002503611, -0.003781541, -0.004586826, -0.004916002, -0.004816285, -0.004387921, -0.003789945, -0.003150825, -0.002614183, -0.002332071, -0.002411079, -0.002879044, -0.003676064, -0.004661755, -0.005632670, -0.006385408, -0.006801228, -0.006846143, -0.006538160, -0.005909969, -0.004982741, -0.003721961, -0.001962710, 3.500578e-04, 0.002880295, 0.004935651, 0.006025189, 0.006123947, 0.005430816, 0.004400683, 0.003650443, 0.003440279, 0.003469131, 0.003409655, 0.003198083, 0.002722458, 0.001909009, 0.0009237891, -1.953957e-05, -6.956606e-04, -9.956822e-04, -9.135438e-04, -6.551662e-04, -0.0005124647, -3.235222e-04, 0.0004284937, 0.002460123, 0.005878239, 0.011293461, 0.01930238, 0.02902866, 0.03811223, 0.04413122, 0.04538404, 0.04058236, 0.03049044, 0.01798196, 0.006286941, -0.002855920, -0.008876536, -0.012008048, -0.012993312, -0.012552290, -0.011106626, -0.008631632, -0.005083978, -6.622375e-04, 0.004069880, 0.008286736, 0.011349065, 0.01292439, 0.01298040, 0.011846174, 0.010011178, 0.007896009, 0.005659386, 0.003402855, 0.001205136, -8.370329e-04, -0.0026844235, -0.004181362, -0.005205899, -0.005861433, -0.006076603, -0.005957583, -0.005699112, -0.005421880, -0.0051503787, -4.248308e-03, -0.0023199115, 0.0008804875, 0.005042596, 0.008922619, 0.011093968, 0.010581847, 0.007941128, 0.003746438, -1.394422e-03, -0.006247597, -0.010319945, -0.012831115],
    ];

    const Y_LABELS = ['Heating value', 'C', 'H', 'N', 'Starch', 'Ash'];

    const Y_DATA = [
        'B1' => [18373, 41.61500, 6.565000, 1.810000, 59.92, 2.39],
        'B2' => [18536, 41.40500, 6.545000, 1.910000, 60.44, 2.19],
        'B3' => [18418, 41.79000, 6.505000, 1.710000, 60.92, 2.44],
        'M1' => [18551, 41.92500, 6.580000, 1.015000, 74.82, 1.36],
        'M2' => [18561, 41.66500, 6.695000, 0.915000, 76.54, 1.23],
        'M3' => [18594, 42.14667, 6.753334, 1.176667, 72.22, 1.18],
        'R1' => [18151, 40.64500, 6.670000, 1.340000, 61.95, 1.57],
        'R2' => [18244, 41.30500, 6.805000, 1.755000, 60.62, 1.57],
        'R3' => [18230, 41.13500, 6.885000, 1.630000, 65.63, 1.62],
        'T1' => [18143, 40.36500, 6.835000, 1.530000, 70.96, 1.58],
        'T2' => [18317, 41.01000, 6.915000, 1.805000, 68.85, 1.85],
        'T3' => [18255, 41.25000, 6.905000, 1.465000, 70.84, 1.45],
        'W1' => [18594, 41.08000, 6.800000, 2.145000, 62.65, 1.84],
        'W2' => [18462, 41.45500, 6.770000, 1.840000, 68.42, 1.52],
        'W3' => [18406, 40.93000, 6.640000, 1.620000, 69.92, 1.60],
    ];

    const YSC_LABELS = ['Heating value', 'C', 'H', 'N', 'Starch', 'Ash'];

    const YSC_DATA = [
        'B1' => [-0.1005049,  0.6265746, -1.1716630,  0.6767926, -1.2580072,  1.8128533],
        'B2' => [ 0.9233889,  0.1882929, -1.3185289,  0.9682348, -1.1653494,  1.2929146],
        'B3' => [ 0.1821652,  0.9918026, -1.6122606,  0.3853510, -1.0798193,  1.9428380],
        'M1' => [ 1.0176123,  1.2735477, -1.0615151, -1.6401702,  1.3969938, -0.8648315],
        'M2' => [ 1.0804278,  0.7309243, -0.2170342, -1.9316121,  1.7034774, -1.2027917],
        'M3' => [ 1.2877192,  1.7361753,  0.2113251, -1.1690060,  0.9337054, -1.3327767],
        'R1' => [-1.4950106, -1.3978438, -0.4006172, -0.6929840, -0.8962852, -0.3188956],
        'R2' => [-0.9108258, -0.0204066,  0.5907251,  0.5164999, -1.1332755, -0.3188956],
        'R3' => [-0.9987676, -0.3752053,  1.1781915,  0.1521975, -0.2405541, -0.1889112],
        'T1' => [-1.5452631, -1.9822088,  0.8110254, -0.1392444,  0.7091881, -0.2928989],
        'T2' => [-0.4522721, -0.6360836,  1.3984889,  0.6622205,  0.3332114,  0.4090184],
        'T3' => [-0.8417287, -0.1351937,  1.3250574, -0.3286816,  0.6878051, -0.6308591],
        'W1' => [ 1.2877192, -0.4899845,  0.5540116,  1.6531233, -0.7715535,  0.3830215],
        'W2' => [ 0.4585537,  0.2926505,  0.3337114,  0.7642255,  0.2565905, -0.4488805],
        'W3' => [ 0.1067865, -0.8030416, -0.6209175,  0.1230533,  0.5238725, -0.2409050],
    ];

    const SCALED_CENTER = [
        'Heating value' => 18389.000000,
        'C'             => 41.314778,
        'H'             => 6.724556,
        'N'             => 1.577778,
        'Starch'        => 66.979999,
        'Ash'           => 1.692667,
    ];

    const SCALED_SCALE = [
        'Heating value' => 159.1961952,
        'C'             => 0.4791506,
        'H'             => 0.1361787,
        'N'             => 0.3431216,
        'Starch'        => 5.6120510,
        'Ash'           => 0.3846607,
    ];

    /**
     * Cereal names
     *
     * @return string[]
     */
    public function getCereals(): array
    {
        return self::CEREALS;
    }

    /**
     * Raw data without labels
     * [[0.002682755, 0.003370673, 0.004085942, ... ], [0.002781597, 0.003474863, 0.004191472, ... ], ... ]
     *
     * @return number[][]
     */
    public function getXData(): array
    {
        return \array_values(self::X_DATA);
    }

    /**
     * Raw data with each observation labeled
     * ['B1' => ['X1126.0' => 0.002682755, 'X1134.0' => 0.003370673, 'X1142.0' => 0.004085942, ... ]]
     *
     * @return number[]
     */
    public function getLabeledXData(): array
    {
        return array_map(
            function (array $data) {
                return array_combine(self::X_LABELS, $data);
            },
            self::X_DATA
        );
    }

    /**
     * Raw data without labels
     * [[18373, 41.61500, 6.565000, ... ], [18536, 41.40500, 6.545000, ... ], ... ]
     *
     * @return number[][]
     */
    public function getYData(): array
    {
        return \array_values(self::Y_DATA);
    }

    /**
     * Raw data with each observation labeled
     * ['B1' => ['Heating value' => 18373, 'C' => 41.61500, 'H' => 6.565000, ... ]]
     *
     * @return number[]
     */
    public function getLabeledYData(): array
    {
        return array_map(
            function (array $data) {
                return array_combine(self::Y_LABELS, $data);
            },
            self::Y_DATA
        );
    }

    /**
     * Raw data without labels
     * [[-0.1005049, 0.6265746, -1.1716630, ... ], [0.9233889, 0.1882929, -1.3185289, ... ], ... ]
     *
     * @return number[][]
     */
    public function getYscData(): array
    {
        return \array_values(self::YSC_DATA);
    }

    /**
     * Raw data with each observation labeled
     * ['B1' => ['Heating value' => -0.1005049, 'C' => 0.6265746, 'H' => -1.1716630, ... ]]
     *
     * @return number[]
     */
    public function getLabeledYscData(): array
    {
        return array_map(
            function (array $data) {
                return array_combine(self::YSC_LABELS, $data);
            },
            self::YSC_DATA
        );
    }

    /**
     * @return array
     */
    public function getScaledCenter(): array
    {
        return self::SCALED_CENTER;
    }

    /**
     * @return array
     */
    public function getScaledScale(): array
    {
        return self::SCALED_SCALE;
    }
}
